<?php
session_start();
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$success = false;
$error = "";

// Ambil user ID dari session
$userId = $_SESSION['user']['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $userId !== null) {
    // Ambil inputan...
    $nama = trim($_POST['nama'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $nomor_hp = trim($_POST['nomor_hp'] ?? '');
    $surat_lamaran = trim($_POST['surat_lamaran'] ?? '');

    // CEK: Sudah melamar belum?
    $cekStmt = $conn->prepare("SELECT id FROM pelamar WHERE user_id = ? AND lowongan_id = ?");
    $cekStmt->bind_param("ii", $userId, $id);
    $cekStmt->execute();
    $cekResult = $cekStmt->get_result();

    if ($cekResult->num_rows > 0) {
        $error = "Anda sudah pernah melamar lowongan ini.";
    } else {
        // Lanjut proses upload file dan insert
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        function uploadFile($fileInputName, $uploadDir, &$error) {
            if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES[$fileInputName]['tmp_name'];
                $fileName = basename($_FILES[$fileInputName]['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($fileExt !== "pdf") {
                    $error = "File $fileInputName harus berformat PDF.";
                    return false;
                }

                $baseName = pathinfo($fileName, PATHINFO_FILENAME);
                $sanitizedBase = preg_replace("/[^a-zA-Z0-9_-]/", "", $baseName);
                $newFileName = uniqid() . "_" . $sanitizedBase . ".pdf";

                $targetPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    return $newFileName;
                } else {
                    $error = "Gagal mengupload file $fileInputName.";
                    return false;
                }
            }
            return null;
        }

        $cvFile = uploadFile('cv', $uploadDir, $error);
        $portofolioFile = uploadFile('portofolio', $uploadDir, $error);

        if (!$error && $nama && $tanggal_lahir && $email && $nomor_hp && $cvFile) {
            if ($portofolioFile === null) {
                $portofolioFile = "";
            }

            $stmt = $conn->prepare("INSERT INTO pelamar (user_id, lowongan_id, nama, tanggal_lahir, email, nomor_hp, cv, portofolio, surat_lamaran, tanggal_lamar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("iisssssss", $userId, $id, $nama, $tanggal_lahir, $email, $nomor_hp, $cvFile, $portofolioFile, $surat_lamaran);

            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = "Gagal menyimpan data pelamar: " . $stmt->error;
            }
        } elseif (!$error) {
            $error = "Data wajib belum lengkap atau file CV belum diupload.";
        }
    }
} elseif ($userId === null) {
    $error = "Anda harus login terlebih dahulu.";
}

// Ambil data lowongan
if ($id) {
    $stmtLowongan = $conn->prepare("SELECT title, perusahaan FROM lowongan WHERE id = ?");
    $stmtLowongan->bind_param("i", $id);
    $stmtLowongan->execute();
    $resLowongan = $stmtLowongan->get_result();
    $lowongan = $resLowongan->fetch_assoc();
} else {
    $lowongan = ['title' => 'Lowongan Tidak Ditemukan', 'perusahaan' => ''];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../Assets/css/pengajuan.css?v=<?= time(); ?>" />
    <title>Pengajuan Lamaran</title>
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <a href="detail.php?id=<?= htmlspecialchars($id) ?>" class="back-button">← Kembali ke Detail</a>
    <div class="banner">
        <img src="/Pic/BannerPT/PTLestari.jpg" alt="Banner Lowongan Kerja" />
    </div>
    <div class="job-info">
        <h2><?= htmlspecialchars($lowongan['title']) ?></h2>
        <p><?= htmlspecialchars($lowongan['perusahaan']) ?></p>
    </div>

    <?php if ($success): ?>
        <p id="successMessage" style="color: green; font-weight: bold;">Berhasil Melamar!</p>
    <?php elseif ($error): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form class="form-lamar" id="applicationForm" method="post" enctype="multipart/form-data">
        <h2>Formulir Pengajuan Lamaran Kerja</h2>
        <div class="form-group">
            <label for="nama">Nama Lengkap :</label>
            <input type="text" id="nama" name="nama" required value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" />
        </div>

        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir :</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? '') ?>" />
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        </div>

        <div class="form-group">
            <label for="nomor_hp">Nomor HP :</label>
            <input type="tel" id="nomor_hp" name="nomor_hp" required value="<?= htmlspecialchars($_POST['nomor_hp'] ?? '') ?>" />
        </div>

        <div class="form-group">
            <label for="cv">CV (PDF) :</label>
            <input type="file" id="cv" name="cv" accept="application/pdf" required />
        </div>

        <div class="form-group">
            <label for="portofolio">Portofolio (Opsional) :</label>
            <input type="file" id="portofolio" name="portofolio" accept="application/pdf" />
        </div>

        <div class="form-group">
            <label for="surat_lamaran">Surat Lamaran (Opsional) :</label>
            <textarea id="surat_lamaran" name="surat_lamaran" rows="4" cols="50"><?= htmlspecialchars($_POST['surat_lamaran'] ?? '') ?></textarea>
        </div>

        <div class="button-container">
            <button type="submit" class="submit-button">Kirim Lamaran</button>
            <a href="home.php" class="search-button">Cari Lowongan Lain?</a>
        </div>
    </form>
</main>

<?php include 'footer.php'; ?>

</body>
</html>