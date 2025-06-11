<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/detail.css?v=<?= time(); ?>">
    <title>Detail Lowongan</title>
</head>
<body>
    <?php
    session_start();
    require 'db.php';
    include 'header.php';
    
    $userLoggedIn = isset($_SESSION['user']);
    $id = $_GET['id'];
    $query = $conn->prepare("SELECT * FROM lowongan WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $lowongan = $result->fetch_assoc();

    $sudahMelamar = false;

    if ($userLoggedIn) {
        $userId = $_SESSION['user']['id']; // pastikan `id` ada di session user
        $cekStmt = $conn->prepare("SELECT id FROM pelamar WHERE user_id = ? AND lowongan_id = ?");
        $cekStmt->bind_param("ii", $userId, $id);
        $cekStmt->execute();
        $cekResult = $cekStmt->get_result();
        $sudahMelamar = $cekResult->num_rows > 0;
    }


    if (!$lowongan) {
        echo "Lowongan tidak ditemukan.";
        exit;
    }

    $pertanyaan = explode("|", $lowongan['pertanyaan']);
    ?>

    <main class="container">
        <div class="container">
            <div class="left-column">
                <a href="home.php" class="back-button">← Kembali ke Lowongan</a>

                <img src="uploads/banner/<?= htmlspecialchars($lowongan['banner']) ?>" alt="Logo Perusahaan" class="banner">
                <img src="uploads/logo/<?= htmlspecialchars($lowongan['logo']) ?>" alt="Logo Perusahaan" class="logo">
                <div class="job-title"><?= htmlspecialchars($lowongan['title']) ?></div>
                <div class="company-name"><?= htmlspecialchars($lowongan['perusahaan']) ?></div>
                <p>📍 <?= htmlspecialchars($lowongan['lokasi']) ?></p>
                <p>💻 <?= htmlspecialchars($lowongan['bidang']) ?></p>
                <p>⏳ <?= htmlspecialchars($lowongan['tipe']) ?></p>
                <?php
                $gajiFormatted = '';
                if (strpos($lowongan['gaji'], '-') !== false) {
                    list($min, $max) = explode('-', $lowongan['gaji']);
                    $gajiFormatted = 'Rp ' . number_format((float)str_replace(['.', ','], '', $min), 0, ',', '.') . ' - Rp ' . number_format((float)str_replace(['.', ','], '', $max), 0, ',', '.');
                } else {
                    $gajiFormatted = 'Rp ' . number_format((float)str_replace(['.', ','], '', $lowongan['gaji']), 0, ',', '.');
                }
                ?>
                <p>💰 <?= $gajiFormatted ?></p>
                <div class="buttons">
                    <?php $userLoggedIn = isset($_SESSION['user']); ?>
                    <?php if (!$userLoggedIn): ?>
                        <button class="apply-btn" id="applyBtn">Buat Lamaran</button>
                    <?php else: ?>
                        <?php if ($sudahMelamar): ?>
                            <p style="color: red; font-weight: bold;">Anda sudah pernah melamar lowongan ini.</p>
                        <?php else: ?>
                            <a href="pengajuan.php?id=<?= $lowongan['id'] ?>" class="apply-btn">Buat Lamaran</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right-column">
                <h2>Deskripsi Pekerjaan</h2>
                <?php
                foreach (explode("\n", $lowongan['deskripsi']) as $baris) {
                    echo "<p>" . htmlspecialchars($baris) . "</p>";
                }
                ?>
                <h2>Pertanyaan dari Perusahaan</h2>
                <?php
                $pertanyaanList = explode("\n", $lowongan['pertanyaan']); // atau $detailLowongan['pertanyaan']
                ?>
                <ol>
                    <?php foreach ($pertanyaanList as $q): ?>
                        <li><p><?= htmlspecialchars(trim($q)) ?></p></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    
</body>
</html>