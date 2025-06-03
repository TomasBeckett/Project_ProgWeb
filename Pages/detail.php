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

                <img src="<?= $lowongan['banner'] ?>" alt="Banner" class="banner">
                <img src="<?= $lowongan['logo'] ?>" alt="Logo Perusahaan" class="logo">
                <div class="job-title"><?= htmlspecialchars($lowongan['title']) ?></div>
                <div class="company-name"><?= htmlspecialchars($lowongan['perusahaan']) ?></div>
                <p>📍 <?= htmlspecialchars($lowongan['lokasi']) ?></p>
                <p>💻 <?= htmlspecialchars($lowongan['bidang']) ?></p>
                <p>⏳ <?= htmlspecialchars($lowongan['tipe']) ?></p>
                <p>💰 Rp<?= htmlspecialchars($lowongan['gaji']) ?></p>
                <div class="buttons">
                    <?php $userLoggedIn = isset($_SESSION['user']); ?>
                    <?php if (!$userLoggedIn): ?>
                        <button class="apply-btn" id="applyBtn">Buat Lamaran</button>
                    <?php else: ?>
                        <a href="pengajuan.php?id=<?= $lowongan['id'] ?>" class="apply-btn">Buat Lamaran</a>
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
                <ol>
                    <?php foreach ($pertanyaan as $q): ?>
                        <li><p><?= htmlspecialchars($q) ?></p></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    
</body>
</html>