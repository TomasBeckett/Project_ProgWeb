<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$companyId = $_SESSION['user']['id'];

// Upload Direktori
$logoDir = __DIR__ . '/uploads/logo/';
$bannerDir = __DIR__ . '/uploads/banner/';
if (!file_exists($logoDir)) mkdir($logoDir, 0777, true);
if (!file_exists($bannerDir)) mkdir($bannerDir, 0777, true);

function uploadFile($field, $dir) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
        $filename = time() . '_' . basename($_FILES[$field]['name']);
        $targetPath = $dir . $filename;
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
            return 'uploads/' . basename($dir) . '/' . $filename;
        }
    }
    return null;
}

// --- Handle Request POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_lowongan'])) {
        $title = $_POST['title'] ?? '';
        $bidang = $_POST['bidang'] ?? '';
        $tipe = $_POST['tipe'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $pertanyaan = $_POST['pertanyaan'] ?? '';
        $perusahaan = $_POST['perusahaan'] ?? '';
        $gaji_min = (int) str_replace('.', '', $_POST['gaji_min'] ?? '0');
        $gaji_max = (int) str_replace('.', '', $_POST['gaji_max'] ?? '0');
        $gaji = number_format($gaji_min, 0, ',', '.') . " - " . number_format($gaji_max, 0, ',', '.');
        $logoPath = uploadFile('logo', $logoDir);
        $bannerPath = uploadFile('banner', $bannerDir);
        if (!$logoPath) die("Upload logo gagal atau tidak ada file.");

        $stmt = $conn->prepare("INSERT INTO lowongan (perusahaan_id, title, bidang, tipe, gaji, lokasi, deskripsi, pertanyaan, perusahaan, logo, banner) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssss", $companyId, $title, $bidang, $tipe, $gaji, $lokasi, $deskripsi, $pertanyaan, $perusahaan, $logoPath, $bannerPath);
        $stmt->execute();
        $stmt->close();
        header("Location: pengelola.php");
        exit;
    }

    if (isset($_POST['delete_lowongan'])) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo "<script>alert('ID lowongan tidak valid.'); window.location.href='pengelola.php';</script>";
            exit;
        }
    
        // Cek apakah lowongan milik perusahaan ini
        $stmt = $conn->prepare("SELECT perusahaan_id FROM lowongan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($pemilik);
        $stmt->fetch();
        $stmt->close();
    
        if ($pemilik != $companyId) {
            echo "<script>alert('Akses ditolak.'); window.location.href='pengelola.php';</script>";
            exit;
        }
    
        // Cek apakah ada pelamar di lowongan ini
        $stmt = $conn->prepare("SELECT COUNT(*) FROM pelamar WHERE lowongan_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($countPelamar);
        $stmt->fetch();
        $stmt->close();
    
        if ($countPelamar > 0) {
            echo "<script>alert('Lowongan tidak bisa dihapus karena ada pelamar.'); window.location.href='pengelola.php';</script>";
            exit;
        }
    
        // Jika lolos semua cek, hapus lowongan
        $stmt = $conn->prepare("DELETE FROM lowongan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    
        header("Location: pengelola.php");
        exit;
    }


    if (isset($_POST['edit_lowongan'])) {
        $id = (int) $_POST['id'];
        $stmt = $conn->prepare("SELECT logo, banner FROM lowongan WHERE id = ? AND perusahaan_id = ?");
        $stmt->bind_param("ii", $id, $companyId);
        $stmt->execute();
        $stmt->bind_result($currentLogo, $currentBanner);
        $stmt->fetch();
        $stmt->close();

        $title = $_POST['title'];
        $bidang = $_POST['bidang'];
        $tipe = $_POST['tipe'];
        $lokasi = $_POST['lokasi'];
        $perusahaan = $_POST['perusahaan'];
        $deskripsi = $_POST['deskripsi'];
        $pertanyaan = $_POST['pertanyaan'];
        $gaji_min = (int) str_replace('.', '', $_POST['gaji_min']);
        $gaji_max = (int) str_replace('.', '', $_POST['gaji_max']);
        $gaji = number_format($gaji_min, 0, ',', '.') . " - " . number_format($gaji_max, 0, ',', '.');

        $logoPath = uploadFile('logo', $logoDir) ?? $currentLogo;
        $bannerPath = uploadFile('banner', $bannerDir) ?? $currentBanner;

        $stmt = $conn->prepare("UPDATE lowongan SET title=?, bidang=?, tipe=?, gaji=?, lokasi=?, deskripsi=?, pertanyaan=?, perusahaan=?, logo=?, banner=? WHERE id=? AND perusahaan_id=?");
        $stmt->bind_param("ssssssssssii", $title, $bidang, $tipe, $gaji, $lokasi, $deskripsi, $pertanyaan, $perusahaan, $logoPath, $bannerPath, $id, $companyId);
        $stmt->execute();
        $stmt->close();
        header("Location: pengelola.php");
        exit;
    }
}

// --- Ambil Data Lowongan ---
$stmt = $conn->prepare("SELECT * FROM lowongan WHERE perusahaan_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $companyId);
$stmt->execute();
$result = $stmt->get_result();
$lowongans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// --- Cek apakah ada detail lowongan / pelamar ---
$detailLowongan = null;
$pelamars = [];
if (isset($_GET['detail'])) {
    $id = (int)$_GET['detail'];
    $stmt = $conn->prepare("SELECT * FROM lowongan WHERE id = ? AND perusahaan_id = ?");
    $stmt->bind_param("ii", $id, $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $detailLowongan = $result->fetch_assoc();
    $stmt->close();

    if ($detailLowongan) {
        $stmt = $conn->prepare("SELECT * FROM pelamar WHERE lowongan_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pelamars = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

$detailPelamar = null;
if (isset($_GET['pelamar'])) {
    $pelamarId = (int) $_GET['pelamar'];
    $stmt = $conn->prepare("SELECT pelamar.* FROM pelamar JOIN lowongan ON pelamar.lowongan_id = lowongan.id WHERE pelamar.id = ? AND lowongan.perusahaan_id = ?");
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
    <link rel="stylesheet" href="../Assets/css/pengelola.css?v=<?= time(); ?>">
</head>
<body>

<?php include 'header.php'; ?>

<h1>Pengelola Lowongan Perusahaan Anda</h1>

<?php if (!$detailLowongan && !$detailPelamar): ?>

<h2 class="judul">Daftar Lowongan</h2>
<table class="lowongan-table">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Bidang</th>
            <th>Jenis</th>
            <th>Gaji</th>
            <th>Lokasi</th>
            <th>Aksi</th>
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
                    <a href="addlowongan.php?id=<?= $lowongan['id'] ?>">Edit</a>
                    <form action="pengelola.php" method="post" class="inline" style="display:inline;" onsubmit="return confirm('Hapus lowongan ini?');">
                        <input type="hidden" name="id" value="<?= $lowongan['id'] ?>">
                        <button type="submit" name="delete_lowongan" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="addlowongan.php" class="tambah_btn">Tambah Lowongan Baru</a>

<?php elseif ($detailLowongan): ?>
<div class="detail-container">
    <h2>Detail Lowongan: <?= htmlspecialchars($detailLowongan['title']) ?></h2>
    <p><strong>Perusahaan:</strong> <?= htmlspecialchars($detailLowongan['perusahaan']) ?></p>
    <p><strong>Bidang:</strong> <?= htmlspecialchars($detailLowongan['bidang']) ?></p>
    <p><strong>Jenis:</strong> <?= htmlspecialchars($detailLowongan['tipe']) ?></p>

    <?php
    // Format gaji agar konsisten Rp xxx - Rp yyy
    $gaji_min = '';
    $gaji_max = '';
    $gajiFormatted = '';

    if (strpos($detailLowongan['gaji'], '-') !== false) {
        list($min, $max) = explode('-', $detailLowongan['gaji']);
        $gaji_min = trim(str_replace(['Rp', '.', ','], '', $min));
        $gaji_max = trim(str_replace(['Rp', '.', ','], '', $max));

        $gajiFormatted = 'Rp ' . number_format((int)$gaji_min, 0, ',', '.') . ' - Rp ' . number_format((int)$gaji_max, 0, ',', '.');
    } else {
        $gaji_min = trim(str_replace(['Rp', '.', ','], '', $detailLowongan['gaji']));
        $gaji_max = '';

        $gajiFormatted = 'Rp ' . number_format((int)$gaji_min, 0, ',', '.');
    }
    ?>
    <p><strong>Gaji:</strong> <?= $gajiFormatted ?></p>
    <p><strong>Lokasi:</strong> <?= htmlspecialchars($detailLowongan['lokasi']) ?></p>
    <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($detailLowongan['deskripsi'])) ?></p>

    <?php $pertanyaanList = array_filter(array_map('trim', explode("\n", $detailLowongan['pertanyaan']))); ?>
    <?php if ($pertanyaanList): ?>
    <p><strong>Pertanyaan:</strong></p>
    <ol>
        <?php foreach ($pertanyaanList as $p): ?>
            <li><?= htmlspecialchars($p) ?></li>
        <?php endforeach; ?>
    </ol>
    <?php endif; ?>

    <p><strong>Logo:</strong><br>
        <img src="../<?= htmlspecialchars($detailLowongan['logo']) ?>" alt="Logo" width="100">
    </p>

    <?php if (!empty($detailLowongan['banner'])): ?>
    <p><strong>Banner:</strong><br>
        <img src="../<?= htmlspecialchars($detailLowongan['banner']) ?>" alt="Banner" width="200">
    </p>
    <?php endif; ?>

    <h3>Daftar Pelamar</h3>
    <?php if ($pelamars): ?>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
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
</div>

<?php elseif ($detailPelamar): ?>
<div class="detail-container">
    <h2>Detail Pelamar: <?= htmlspecialchars($detailPelamar['nama']) ?></h2>
    <p><strong>Tanggal Lahir:</strong> <?= htmlspecialchars($detailPelamar['tanggal_lahir']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($detailPelamar['email']) ?></p>
    <p><strong>Nomor HP:</strong> <?= htmlspecialchars($detailPelamar['nomor_hp']) ?></p>
    <p><strong>CV:</strong> 
        <?php if (!empty($detailPelamar['cv'])): ?>
            <a href="../<?= htmlspecialchars($detailPelamar['cv']) ?>" target="_blank" rel="noopener noreferrer">Lihat CV</a>
        <?php else: ?>
            Tidak ada CV
        <?php endif; ?>
    </p>

    <p><strong>Portofolio:</strong> 
        <?php if (!empty($detailPelamar['portofolio'])): ?>
            <!-- Jika portofolio adalah link/file -->
            <a href="<?= htmlspecialchars($detailPelamar['portofolio']) ?>" target="_blank" rel="noopener noreferrer">Lihat Portofolio</a>

            <!-- Jika portofolio adalah teks biasa, pakai ini (hilangkan komentar bagian atas) -->
            <!-- <?= nl2br(htmlspecialchars($detailPelamar['portofolio'])) ?> -->
        <?php else: ?>
            Tidak ada Portofolio
        <?php endif; ?>
    </p>
    <p><strong>Surat Lamaran:</strong> 
    <?php 
        if (!empty(trim($detailPelamar['surat_lamaran']))) {
            echo nl2br(htmlspecialchars($detailPelamar['surat_lamaran']));
        } else {
            echo "Kosong";
        }
    ?>
    </p>

        
    <p><a href="pengelola.php?detail=<?= $detailPelamar['lowongan_id'] ?>">Kembali ke lowongan</a></p>
</div>  <!-- Tutup div detail-container di sini -->

<?php elseif (isset($_GET['edit'])): ?>

<?php
$editId = (int) $_GET['edit'];
$stmt = $conn->prepare("SELECT * FROM lowongan WHERE id = ? AND perusahaan_id = ?");
$stmt->bind_param("ii", $editId, $companyId);
$stmt->execute();
$result = $stmt->get_result();
$editLowongan = $result->fetch_assoc();
$stmt->close();

if (!$editLowongan):
?>
    <p>Lowongan tidak ditemukan atau akses ditolak.</p>
<?php else: ?>

<?php
$gaji_min = '';
$gaji_max = '';
if (strpos($editLowongan['gaji'], '-') !== false) {
    list($min, $max) = explode('-', $editLowongan['gaji']);
    $gaji_min = trim(str_replace(['Rp', '.', ','], '', $min));
    $gaji_max = trim(str_replace(['Rp', '.', ','], '', $max));
}
?>

<h2>Edit Lowongan: <?= htmlspecialchars($editLowongan['title']) ?></h2>
<form action="pengelola.php" method="post" enctype="multipart/form-data">
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

    <label>Gaji Minimal:<br>
        <input type="text" name="gaji_min" value="<?= htmlspecialchars($gaji_min) ?>" required>
    </label><br>

    <label>Gaji Maksimal:<br>
        <input type="text" name="gaji_max" value="<?= htmlspecialchars($gaji_max) ?>" required>
    </label><br>

    <label>Lokasi:<br>
        <input type="text" name="lokasi" value="<?= htmlspecialchars($editLowongan['lokasi']) ?>" required>
    </label><br>

    <label>Perusahaan:<br>
        <input type="text" name="perusahaan" value="<?= htmlspecialchars($editLowongan['perusahaan']) ?>" required>
    </label><br>

    <label>Deskripsi:<br>
        <textarea name="deskripsi" required><?= htmlspecialchars($editLowongan['deskripsi']) ?></textarea>
    </label><br>

    <label>Pertanyaan (satu per baris):<br>
        <textarea name="pertanyaan"><?= htmlspecialchars($editLowongan['pertanyaan']) ?></textarea>
    </label><br>

    <label>Logo Saat Ini:<br>
        <img src="../<?= htmlspecialchars($editLowongan['logo']) ?>" alt="Logo" width="100"><br>
        <input type="file" name="logo" accept="image/*">
        <small>Abaikan jika tidak ingin ganti logo.</small>
    </label><br>

    <label>Banner Saat Ini:<br>
        <?php if (!empty($editLowongan['banner'])): ?>
            <img src="../<?= htmlspecialchars($editLowongan['banner']) ?>" alt="Banner" width="200"><br>
        <?php else: ?>
            <em>Tidak ada banner</em><br>
        <?php endif; ?>
        <input type="file" name="banner" accept="image/*">
        <small>Abaikan jika tidak ingin ganti banner.</small>
    </label><br>

    <button type="submit" name="edit_lowongan">Simpan Perubahan</button>
</form>

<p><a href="pengelola.php">Batal</a></p>

<?php endif; ?>

<?php endif; ?>

<?php include 'footer.php'; ?>

</body>
</html>



