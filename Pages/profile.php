<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$companyId = $_SESSION['user']['id'];

// Ambil data perusahaan dari DB
$stmt = $conn->prepare("SELECT * FROM perusahaan WHERE id = ?");
$stmt->bind_param("i", $companyId);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_perusahaan = $_POST['nama_perusahaan'] ?? $company['nama_perusahaan'];

    // Upload logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoName = time() . '_' . basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'], 'uploads/logo/' . $logoName);
    } else {
        $logoName = $company['logo']; // tetap pakai lama
    }

    // Upload banner
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $bannerName = time() . '_' . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], 'uploads/banner/' . $bannerName);
    } else {
        $bannerName = $company['banner'];
    }

    // Update DB
    $stmt = $conn->prepare("UPDATE perusahaan SET nama_perusahaan=?, logo=?, banner=? WHERE id=?");
    $stmt->bind_param("sssi", $nama_perusahaan, $logoName, $bannerName, $companyId);
    $stmt->execute();
    $stmt->close();

    header("Location: profile.php?success=1");
    exit;
}
?>

<!-- Form edit profile -->
<form method="POST" enctype="multipart/form-data">
    <label>Nama Perusahaan:</label>
    <input type="text" name="nama_perusahaan" value="<?= htmlspecialchars($company['nama_perusahaan'] ?? '') ?>" required><br>

    <label>Logo:</label>
    <input type="file" name="logo"><br>
    <?php if (!empty($company['logo'])): ?>
        <img src="uploads/logo/<?= htmlspecialchars($company['logo']) ?>" height="60"><br>
    <?php endif; ?>

    <label>Banner:</label>
    <input type="file" name="banner"><br>
    <?php if (!empty($company['banner'])): ?>
        <img src="uploads/banner/<?= htmlspecialchars($company['banner']) ?>" height="60"><br>
    <?php endif; ?>

    <button type="submit">Simpan Profil</button>
</form>
