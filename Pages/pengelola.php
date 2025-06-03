<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$companyId = $_SESSION['user']['id'];

// Folder upload logo & banner
$logoDir = __DIR__ . '/uploads/logo/';
$bannerDir = __DIR__ . '/uploads/banner/';

if (!file_exists($logoDir)) mkdir($logoDir, 0777, true);
if (!file_exists($bannerDir)) mkdir($bannerDir, 0777, true);

// Fungsi bantu upload file, return path relatif jika berhasil, null jika gagal
function uploadFile($fileInputName, $uploadDir) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
        $filename = time() . '_' . basename($_FILES[$fileInputName]['name']);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetPath)) {
            // Kembalikan path relatif untuk disimpan di DB, sesuaikan dengan folder public web kamu
            return 'uploads/' . basename($uploadDir) . '/' . $filename;
        }
    }
    return null;
}

// Handle tambah, edit, hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // TAMBAH LOWONGAN
    if (isset($_POST['add_lowongan'])) {
        $title = $_POST['title'] ?? '';
        $bidang = $_POST['bidang'] ?? '';
        $tipe = $_POST['tipe'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $pertanyaan = $_POST['pertanyaan'] ?? '';
        $perusahaan = $_POST['perusahaan'] ?? '';

        // Format gaji min dan max, hapus titik ribuan, cast ke int
        $gaji_min = (int) str_replace('.', '', $_POST['gaji_min'] ?? '0');
        $gaji_max = (int) str_replace('.', '', $_POST['gaji_max'] ?? '0');
        $gaji = $gaji_min . " - " . $gaji_max;

        // Upload logo dan banner
        $logoPath = uploadFile('logo', $logoDir);
        $bannerPath = uploadFile('banner', $bannerDir);

        // Logo wajib diisi
        if (!$logoPath) {
            die("Upload logo gagal atau tidak ada file.");
        }

        $stmt = $conn->prepare("INSERT INTO lowongan (perusahaan_id, title, bidang, tipe, gaji, lokasi, deskripsi, pertanyaan, perusahaan, logo, banner) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssss", $companyId, $title, $bidang, $tipe, $gaji, $lokasi, $deskripsi, $pertanyaan, $perusahaan, $logoPath, $bannerPath);
        $stmt->execute();
        $stmt->close();

        header("Location: pengelola.php");
        exit;
    }

    // EDIT LOWONGAN
    if (isset($_POST['edit_lowongan'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) die("ID lowongan tidak valid.");

        // Cek kepemilikan lowongan
        $stmt = $conn->prepare("SELECT perusahaan_id FROM lowongan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($pemilik);
        $stmt->fetch();
        $stmt->close();

        if ($pemilik != $companyId) die("Akses ditolak.");

        $title = $_POST['title'] ?? '';
        $bidang = $_POST['bidang'] ?? '';
        $tipe = $_POST['tipe'] ?? '';
        $gaji = $_POST['gaji'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        $stmt = $conn->prepare("UPDATE lowongan SET title=?, bidang=?, tipe=?, gaji=?, lokasi=?, deskripsi=? WHERE id=?");
        $stmt->bind_param("ssssssi", $title, $bidang, $tipe, $gaji, $lokasi, $deskripsi, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: pengelola.php");
        exit;
    }

    // HAPUS LOWONGAN
    if (isset($_POST['delete_lowongan'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) die("ID lowongan tidak valid.");

        // Cek kepemilikan lowongan
        $stmt = $conn->prepare("SELECT perusahaan_id FROM lowongan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($pemilik);
        $stmt->fetch();
        $stmt->close();

        if ($pemilik != $companyId) die("Akses ditolak.");

        // Cek pelamar
        $stmt = $conn->prepare("SELECT COUNT(*) FROM pelamar WHERE lowongan_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($countPelamar);
        $stmt->fetch();
        $stmt->close();

        if ($countPelamar > 0) {
            die("Lowongan tidak bisa dihapus karena sudah ada pelamar.");
        }

        // Hapus lowongan
        $stmt = $conn->prepare("DELETE FROM lowongan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: pengelola.php");
        exit;
    }
}

// Ambil daftar lowongan perusahaan
$stmt = $conn->prepare("SELECT * FROM lowongan WHERE perusahaan_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $companyId);
$stmt->execute();
$result = $stmt->get_result();
$lowongans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Detail lowongan & pelamar
$detailLowongan = null;
$pelamars = [];
if (isset($_GET['detail'])) {
    $detailId = (int)$_GET['detail'];
    $stmt = $conn->prepare("SELECT * FROM lowongan WHERE id = ? AND perusahaan_id = ?");
    $stmt->bind_param("ii", $detailId, $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $detailLowongan = $result->fetch_assoc();
    $stmt->close();

    if ($detailLowongan) {
        $stmt = $conn->prepare("SELECT * FROM pelamar WHERE lowongan_id = ?");
        $stmt->bind_param("i", $detailId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pelamars = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

// Detail pelamar
$detailPelamar = null;
if (isset($_GET['pelamar'])) {
    $pelamarId = (int)$_GET['pelamar'];

    $stmt = $conn->prepare("
        SELECT pelamar.* FROM pelamar
        JOIN lowongan ON pelamar.lowongan_id = lowongan.id
        WHERE pelamar.id = ? AND lowongan.perusahaan_id = ?
    ");
    $stmt->bind_param("ii", $pelamarId, $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $detailPelamar = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Pengelola Lowongan - Perusahaan</title>
    <link rel="stylesheet" href="../Assets/css/web.css?v=<?= time(); ?>">
    <style>
        table {border-collapse: collapse; width: 100%;}
        th, td {border: 1px solid #ddd; padding: 8px;}
        th {background: #f4f4f4;}
        .btn {padding: 6px 10px; background: #007bff; color: white; border: none; cursor: pointer;}
        .btn-danger {background: #dc3545;}
        .btn-link {background: none; color: #007bff; text-decoration: underline; cursor: pointer;}
        form.inline {display: inline;}
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Pengelola Lowongan Perusahaan Anda</h1>

<?php if (!$detailLowongan && !$detailPelamar): ?>

<h2>Daftar Lowongan</h2>
<table>
    <thead>
        <tr>
            <th>Judul</th><th>Bidang</th><th>Jenis</th><th>Gaji</th><th>Lokasi</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lowongans as $lowongan): ?>
            <tr>
                <td><?= htmlspecialchars($lowongan['title']) ?></td>
                <td><?= htmlspecialchars($lowongan['bidang']) ?></td>
                <td><?= htmlspecialchars($lowongan['tipe']) ?></td>
                <td><?= htmlspecialchars($lowongan['gaji']) ?></td>
                <td><?= htmlspecialchars($lowongan['lokasi']) ?></td>
                <td>
                    <a href="pengelola.php?detail=<?= $lowongan['id'] ?>">Detail</a> |
                    <a href="pengelola.php?edit=<?= $lowongan['id'] ?>">Edit</a> |
                    <form action="pengelola.php" method="post" class="inline" onsubmit="return confirm('Hapus lowongan ini?');">
                        <input type="hidden" name="id" value="<?= $lowongan['id'] ?>">
                        <button type="submit" name="delete_lowongan" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Tambah Lowongan Baru</h2>
<form action="pengelola.php" method="post" enctype="multipart/form-data">
    <label>Judul Lowongan:<br>
        <input type="text" name="title" required>
    </label><br>
    <label>Bidang:<br>
        <input type="text" name="bidang" required>
    </label><br>
    <label>Jenis Pekerjaan:<br>
        <input type="text" name="tipe" required>
    </label><br>
    <label>Gaji Minimum:<br>
        <input type="text" name="gaji_min" pattern="[0-9\.]+" title="Masukkan angka, gunakan titik untuk ribuan" required>
    </label><br>
    <label>Gaji Maksimum:<br>
        <input type="text" name="gaji_max" pattern="[0-9\.]+" title="Masukkan angka, gunakan titik untuk ribuan" required>
    </label><br>
    <label>Lokasi:<br>
        <input type="text" name="lokasi" required>
    </label><br>
    <label>Deskripsi:<br>
        <textarea name="deskripsi" required></textarea>
    </label><br>
    <label>Pertanyaan:<br>
        <textarea name="pertanyaan" required></textarea>
    </label><br>
    <label>Perusahaan:<br>
        <input type="text" name="perusahaan" required>
    </label><br>
    <label>Logo:<br>
        <input type="file" name="logo" accept="image/*" required>
    </label><br>
    <label>Banner:<br>
        <input type="file" name="banner" accept="image/*">
    </label><br>
    <button type="submit" name="add_lowongan" class="btn">Tambah</button>
</form>

<?php elseif ($detailLowongan): ?>

<h2>Detail Lowongan: <?= htmlspecialchars($detailLowongan['title']) ?></h2>

<p><strong>Bidang:</strong> <?= htmlspecialchars($detailLowongan['bidang']) ?></p>
<p><strong>Jenis:</strong> <?= htmlspecialchars($detailLowongan['tipe']) ?></p>
<p><strong>Gaji:</strong> <?= htmlspecialchars($detailLowongan['gaji']) ?></p>
<p><strong>Lokasi:</strong> <?= htmlspecialchars($detailLowongan['lokasi']) ?></p>
<p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($detailLowongan['deskripsi'])) ?></p>
<p><strong>Pertanyaan:</strong> <?= nl2br(htmlspecialchars($detailLowongan['pertanyaan'])) ?></p>
<p><strong>Perusahaan:</strong> <?= htmlspecialchars($detailLowongan['perusahaan']) ?></p>
<p><strong>Logo:</strong><br><img src="../<?= htmlspecialchars($detailLowongan['logo']) ?>" alt="Logo" width="100"></p>
<?php if ($detailLowongan['banner']): ?>
<p><strong>Banner:</strong><br><img src="../<?= htmlspecialchars($detailLowongan['banner']) ?>" alt="Banner" width="200"></p>
<?php endif; ?>

<h3>Daftar Pelamar</h3>
<?php if ($pelamars): ?>
<table>
    <thead>
        <tr>
            <th>Nama</th><th>Email</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pelamars as $pelamar): ?>
            <tr>
                <td><?= htmlspecialchars($pelamar['nama']) ?></td>
                <td><?= htmlspecialchars($pelamar['email']) ?></td>
                <td><a href="pengelola.php?pelamar=<?= $pelamar['id'] ?>">Lihat Detail</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>Tidak ada pelamar.</p>
<?php endif; ?>

<p><a href="pengelola.php">Kembali ke daftar lowongan</a></p>

<?php elseif ($detailPelamar): ?>

<h2>Detail Pelamar: <?= htmlspecialchars($detailPelamar['nama']) ?></h2>
<p><strong>Tanggal Lahir:</strong> <?= htmlspecialchars($detailPelamar['tanggal_lahir']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($detailPelamar['email']) ?></p>
<p><strong>Nomor HP:</strong> <?= htmlspecialchars($detailPelamar['nomor_hp']) ?></p>
<p><strong>CV:</strong> 
    <?php if ($detailPelamar['cv']): ?>
        <a href="../<?= htmlspecialchars($detailPelamar['cv']) ?>" target="_blank">Download CV</a>
    <?php else: ?>
        Tidak ada CV
    <?php endif; ?>
</p>
<p><strong>Portofolio:</strong> <?= htmlspecialchars($detailPelamar['portofolio']) ?></p>
<p><strong>Surat Lamaran:</strong> <?= htmlspecialchars($detailPelamar['surat_lamaran']) ?></p>

<p><a href="pengelola.php?detail=<?= $detailPelamar['lowongan_id'] ?>">Kembali ke lowongan</a></p>

<?php elseif (isset($_GET['edit'])):

// Ambil data lowongan untuk edit
$editId = (int)$_GET['edit'];
$stmt = $conn->prepare("SELECT * FROM lowongan WHERE id = ? AND perusahaan_id = ?");
$stmt->bind_param("ii", $editId, $companyId);
$stmt->execute();
$result = $stmt->get_result();
$editLowongan = $result->fetch_assoc();
$stmt->close();

if (!$editLowongan) {
    echo "<p>Lowongan tidak ditemukan atau akses ditolak.</p>";
} else {
?>

<h2>Edit Lowongan: <?= htmlspecialchars($editLowongan['title']) ?></h2>
<form action="pengelola.php" method="post">
    <input type="hidden" name="id" value="<?= $editLowongan['id'] ?>">
    <label>Judul Lowongan:<br>
        <input type="text" name="title" value="<?= htmlspecialchars($editLowongan['title']) ?>" required>
    </label><br>
    <label>Bidang:<br>
        <input type="text" name="bidang" value="<?= htmlspecialchars($editLowongan['bidang']) ?>" required>
    </label><br>
    <label>Jenis Pekerjaan:<br>
        <input type="text" name="tipe" value="<?= htmlspecialchars($editLowongan['tipe']) ?>" required>
    </label><br>
    <label>Gaji:<br>
        <input type="text" name="gaji" value="<?= htmlspecialchars($editLowongan['gaji']) ?>" required>
    </label><br>
    <label>Lokasi:<br>
        <input type="text" name="lokasi" value="<?= htmlspecialchars($editLowongan['lokasi']) ?>" required>
    </label><br>
    <label>Deskripsi:<br>
        <textarea name="deskripsi" required><?= htmlspecialchars($editLowongan['deskripsi']) ?></textarea>
    </label><br>
    <button type="submit" name="edit_lowongan" class="btn">Simpan Perubahan</button>
</form>
<p><a href="pengelola.php">Batal</a></p>

<?php } endif; ?>

<?php include 'footer.php'; ?>

</body>

<script>
document.getElementById('gaji_min').addEventListener('change', function () {
    let val = parseInt(this.value);
    if (val < 0) this.value = 0;
});

document.getElementById('gaji_max').addEventListener('change', function () {
    let val = parseInt(this.value);
    if (val < 0) this.value = 0;
});
</script>

<script>
function formatRupiah(input) {
    let angka = input.value.replace(/\D/g, '');
    input.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.getElementById('gaji_min').addEventListener('input', function () {
    formatRupiah(this);
});
document.getElementById('gaji_max').addEventListener('input', function () {
    formatRupiah(this);
});
</script>

</html>


