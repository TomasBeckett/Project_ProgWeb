<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$companyId = $_SESSION['user']['id'];

$logoDir = __DIR__ . '/uploads/logo/';
$bannerDir = __DIR__ . '/uploads/banner/';

$editMode = false;
$editLowongan = null;

$title = '';
$bidang = '';
$tipe = '';
$gaji_min = '';
$gaji_max = '';
$lokasi = '';
$deskripsi = '';
$pertanyaan = '';
$perusahaan = '';
$logo = '';
$banner = '';

// Ambil data untuk mode edit (GET)
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM lowongan WHERE id = ? AND perusahaan_id = ?");
        $stmt->bind_param("ii", $id, $companyId);
        $stmt->execute();
        $result = $stmt->get_result();
        $editLowongan = $result->fetch_assoc();
        $stmt->close();

        if ($editLowongan) {
            $editMode = true;

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $title = $editLowongan['title'];
                $bidang = $editLowongan['bidang'];
                $tipe = $editLowongan['tipe'];

                if (!empty($editLowongan['gaji'])) {
                    $gajiParts = explode(' - ', $editLowongan['gaji']);
                    $gaji_min = isset($gajiParts[0]) ? str_replace('.', '', $gajiParts[0]) : '';
                    $gaji_max = isset($gajiParts[1]) ? str_replace('.', '', $gajiParts[1]) : '';
                }

                $lokasi = $editLowongan['lokasi'];
                $deskripsi = $editLowongan['deskripsi'];
                $pertanyaan = $editLowongan['pertanyaan'];
                $perusahaan = $editLowongan['perusahaan'];
                $logo = $editLowongan['logo'];
                $banner = $editLowongan['banner'];
            }
        }
    }
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ulang data lama jika edit
    if (isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("SELECT * FROM lowongan WHERE id = ? AND perusahaan_id = ?");
        $stmt->bind_param("ii", $id, $companyId);
        $stmt->execute();
        $result = $stmt->get_result();
        $editLowongan = $result->fetch_assoc();
        $stmt->close();

        if ($editLowongan) {
            $editMode = true;
            $oldLogo = $editLowongan['logo'];
            $oldBanner = $editLowongan['banner'];
        }
    }

    $title = $_POST['title'] ?? '';
    $bidang = $_POST['bidang'] ?? '';
    $tipe = $_POST['tipe'] ?? '';
    $gaji_min = (int) str_replace('.', '', $_POST['gaji_min'] ?? '0');
    $gaji_max = (int) str_replace('.', '', $_POST['gaji_max'] ?? '0');
    $gaji = number_format($gaji_min, 0, ',', '.') . " - " . number_format($gaji_max, 0, ',', '.');
    $lokasi = $_POST['lokasi'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $pertanyaan = $_POST['pertanyaan'] ?? '';
    $perusahaan = $_POST['perusahaan'] ?? '';

    // LOGO
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoTmp = $_FILES['logo']['tmp_name'];
        $logoName = time() . '_' . basename($_FILES['logo']['name']);
        move_uploaded_file($logoTmp, $logoDir . $logoName);
        $logo = $logoName;
    } elseif ($editMode && isset($oldLogo)) {
        $logo = $oldLogo;
    } else {
        $logo = '';
    }

    // BANNER
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $bannerTmp = $_FILES['banner']['tmp_name'];
        $bannerName = time() . '_' . basename($_FILES['banner']['name']);
        move_uploaded_file($bannerTmp, $bannerDir . $bannerName);
        $banner = $bannerName;
    } elseif ($editMode && isset($oldBanner)) {
        $banner = $oldBanner;
    } else {
        $banner = '';
    }

    if ($editMode) {
        $stmt = $conn->prepare("UPDATE lowongan SET title=?, bidang=?, tipe=?, gaji=?, lokasi=?, deskripsi=?, pertanyaan=?, perusahaan=?, logo=?, banner=? WHERE id=? AND perusahaan_id=?");
        $stmt->bind_param("ssssssssssii", $title, $bidang, $tipe, $gaji, $lokasi, $deskripsi, $pertanyaan, $perusahaan, $logo, $banner, $id, $companyId);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO lowongan (title, bidang, tipe, gaji, lokasi, deskripsi, pertanyaan, perusahaan, logo, banner, perusahaan_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $title, $bidang, $tipe, $gaji, $lokasi, $deskripsi, $pertanyaan, $perusahaan, $logo, $banner, $companyId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: pengelola.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title><?= $editMode ? "Edit Lowongan" : "Tambah Lowongan" ?></title>
    <link rel="stylesheet" href="../Assets/css/pengajuan.css?v=<?= time(); ?>" />
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <h2><?= $editMode ? "Edit Lowongan" : "Tambah Lowongan Baru" ?></h2>
    <p><a href="pengelola.php" class="back-button">Kembali ke daftar lowongan</a></p>

    <form id="formPertanyaan" action="addlowongan.php" method="post" enctype="multipart/form-data" class="form-lamar">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $editLowongan['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Judul Lowongan :</label> 
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>
        </div>

        <div class="form-group">
            <label>Bidang :</label>
            <input type="text" name="bidang" value="<?= htmlspecialchars($bidang) ?>" required>
        </div>

        <div class="form-group">
            <label>Jenis Pekerjaan :</label>
            <select name="tipe" required>
                <option value="">-- Pilih Jenis Pekerjaan --</option>
                <?php
                $options = ['Full-Time', 'Part-Time', 'Remote', 'Freelance'];
                foreach ($options as $option) {
                    $sel = ($tipe === $option) ? 'selected' : '';
                    echo "<option value=\"$option\" $sel>$option</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Gaji Minimum :</label>
            <input type="text" id="gaji_min" name="gaji_min" pattern="[0-9\.]+" title="Masukkan angka, gunakan titik untuk ribuan" value="<?= htmlspecialchars($gaji_min) ?>" required>
        </div>

        <div class="form-group">
            <label>Gaji Maksimum :</label>
            <input type="text" id="gaji_max" name="gaji_max" pattern="[0-9\.]+" title="Masukkan angka, gunakan titik untuk ribuan" value="<?= htmlspecialchars($gaji_max) ?>" required>
        </div>

        <div class="form-group">
            <label>Lokasi :</label>
            <input type="text" name="lokasi" value="<?= htmlspecialchars($lokasi) ?>" required>
        </div>

        <div class="form-group">
            <label>Deskripsi :</label>
            <textarea name="deskripsi" required><?= htmlspecialchars($deskripsi) ?></textarea>
        </div>

        <div class="form-group">
            <label>Pertanyaan :</label>
            <textarea name="pertanyaan" id="pertanyaan" required><?= htmlspecialchars($pertanyaan ?: "1. ") ?></textarea>
        </div>

        <div class="form-group">
            <label>Perusahaan :</label>
            <input type="text" name="perusahaan" value="<?= htmlspecialchars($perusahaan) ?>" required>
        </div>

        <div class="form-group">
            <label>Logo :</label>
            <input type="file" name="logo" accept="image/*" <?= $editMode ? '' : 'required' ?>>
                <?php if ($editMode && $logo): ?>
                    <br><small> Logo saat ini: <?= htmlspecialchars($logo) ?></small>
                <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Banner :</label>
            <input type="file" name="banner" accept="image/*">
                <?php if ($editMode && $banner): ?>
                    <br><small> Banner saat ini: <?= htmlspecialchars($banner) ?></small>
                <?php endif; ?>
        </div>

        <div class="button-container" style="margin-left: 0; margin-top: 20;" >
            <button type="submit" name="<?= $editMode ? 'edit_lowongan' : 'add_lowongan' ?>" class="submit-button">
                <?= $editMode ? 'Simpan Perubahan' : 'Tambah' ?>
            </button>
        </div>
    </form>
</main>
    
                
<script>
document.getElementById('gaji_min').addEventListener('change', function () {
    let val = parseInt(this.value.replace(/\./g, ''));
    if (val < 0) this.value = "0";
});

document.getElementById('gaji_max').addEventListener('change', function () {
    let val = parseInt(this.value.replace(/\./g, ''));
    if (val < 0) this.value = "0";
});

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

document.getElementById("pertanyaan").addEventListener("keydown", function(e) {
    const textarea = this;

    if (e.key === "Enter") {
        e.preventDefault();

        const lines = textarea.value.split("\n");
        const lastLine = lines[lines.length - 1];
        const match = lastLine.match(/^(\d+)\.\s/);
        let nextNumber = 1;

        if (match) {
            nextNumber = parseInt(match[1]) + 1;
        } else {
            for (let i = lines.length - 2; i >= 0; i--) {
                const m = lines[i].match(/^(\d+)\.\s/);
                if (m) {
                    nextNumber = parseInt(m[1]) + 1;
                    break;
                }
            }
        }

        textarea.value += `\n${nextNumber}. `;
    }
});

document.getElementById("formPertanyaan").addEventListener("submit", function(e) {
    const textarea = document.getElementById("pertanyaan");
    const cleaned = textarea.value
        .split("\n")
        .map(line => line.replace(/^\d+\.\s*/, ''))
        .join("\n");
    textarea.value = cleaned;
});
</script>

<?php include 'footer.php'; ?>

</body>
</html>
