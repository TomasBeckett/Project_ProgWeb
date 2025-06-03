<?php
$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validasi input sederhana
    $nama = trim($_POST['nama'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $nomor_hp = trim($_POST['nomor_hp'] ?? '');
    $surat_lamaran = trim($_POST['surat_lamaran'] ?? '');

    // Folder upload
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Fungsi upload file
    function uploadFile($fileInputName, $uploadDir, &$error) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES[$fileInputName]['tmp_name'];
            $fileName = basename($_FILES[$fileInputName]['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            // Cek ekstensi file
            if ($fileExt !== "pdf") {
                $error = "File $fileInputName harus berformat PDF.";
                return false;
            }
            // Buat nama unik agar tidak tertimpa
            $newFileName = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_-]/", "", $fileName);
            $targetPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmp, $targetPath)) {
                return $newFileName;
            } else {
                $error = "Gagal mengupload file $fileInputName.";
                return false;
            }
        }
        return null; // tidak upload file
    }

    $cvFile = uploadFile('cv', $uploadDir, $error);
    $portofolioFile = uploadFile('portofolio', $uploadDir, $error);

    if (!$error && $nama && $tanggal_lahir && $email && $nomor_hp && $cvFile) {
        // Simpan data ke database atau file (belum ada DB, jadi cuma sukses)
        $success = true;
    } else {
        if (!$error) $error = "Mohon isi semua field wajib dan upload CV.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../Assets/css/pengajuan.css?v="<?= time(); ?>>
    <title>Pengajuan Lamaran</title>
</head>
<body>
<header>
    <div class="header">
        <div class="logo">
            <table>
                <tr>
                    <td>
                        <a href="http://127.0.0.1:5500/Lowongan.html#">
                            <img src="../Assets/Pic/Other/OPEN.png" width="150px" alt="Logo Lowongan" />
                        </a>
                    </td>
                    <td>
                        <a href="http://127.0.0.1:5500/Lowongan.html#">Kerja</a>
                    </td>
                </tr>
            </table>
        </div>
        <table class="Profile">
            <tr>
                <td>
                    <button class="button" id="loginBtn">Login</button>
                </td>
            </tr>
        </table>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Login</h2>
            <form method="post" action="login_process.php">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" class="button">Masuk</button>
                <p>Belum punya akun? <a href="#">Registrasi</a></p>
            </form>
        </div>
    </div>
</header>

<main>
    <a href="detail.php?id=<?= $row['id'] ?>" class="back-button">← Kembali ke Detail</a>
    <div class="banner">
        <img src="/Pic/BannerPT/PTLestari.jpg" alt="Banner Lowongan Kerja" />
    </div>
    <div class="job-info">
        <h2>IT WEB PROGRAMMER</h2>
        <p>PT Sumber Indah Lestari (DAN+DAN)</p>
    </div>

    <?php if ($success): ?>
        <p id="successMessage" style="color: green; font-weight: bold;">Berhasil Melamar!</p>
    <?php elseif ($error): ?>
        <p style="color: red; font-weight: bold;"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>

    <form class="form-lamar" id="applicationForm" method="post" enctype="multipart/form-data">
        <h2>Formulir Pengajuan Lamaran Kerja</h2>
        <div class="form-group">
            <label for="nama">Nama Lengkap :</label>
            <input type="text" id="nama" name="nama" required value="<?=htmlspecialchars($_POST['nama'] ?? '')?>" />
        </div>

        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir :</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required value="<?=htmlspecialchars($_POST['tanggal_lahir'] ?? '')?>" />
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>" />
        </div>

        <div class="form-group">
            <label for="nomor_hp">Nomor HP :</label>
            <input type="tel" id="nomor_hp" name="nomor_hp" required value="<?=htmlspecialchars($_POST['nomor_hp'] ?? '')?>" />
        </div>

        <div class="form-group">
            <label for="cv">CV :</label>
            <input type="file" id="cv" name="cv" accept="application/pdf" required />
        </div>

        <div class="form-group">
            <label for="portofolio">Portofolio (Opsional) :</label>
            <input type="file" id="portofolio" name="portofolio" accept="application/pdf" />
        </div>

        <div class="form-group">
            <label for="surat_lamaran">Surat Lamaran (Opsional) :</label>
            <textarea id="surat_lamaran" name="surat_lamaran" rows="4" cols="50"><?=htmlspecialchars($_POST['surat_lamaran'] ?? '')?></textarea>
        </div>

        <div class="button-container">
            <button type="submit" class="submit-button">Kirim Lamaran</button>
            <a href="home.php" class="search-button">Cari Lowongan Lain?</a>
        </div>
    </form>
</main>

<footer>
    <div class="footer-container">
        <div class="footer-logo">
            <h2>JobFinder</h2>
            <p>Temukan pekerjaan impian Anda dengan mudah.</p>
        </div>

        <div class="footer-links">
            <h3>Link Cepat</h3>
            <ul>
                <li><a href="http://127.0.0.1:5500/Lowongan.html">Beranda</a></li>
                <li><a href="http://127.0.0.1:5500/Lowongan.html">Lowongan</a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h3>Kontak</h3>
            <p>Email: support@OpenKerja.com</p>
            <p>Telepon: +62 812-9999-9999</p>
        </div>

        <div class="footer-social">
            <h3>Ikuti Kami</h3>
            <a href="#"><i class="fab fa-facebook"></i>OpenKerja</a>
            <a href="#"><i class="fab fa-twitter"></i>OpenKerja</a>
            <a href="#"><i class="fab fa-instagram"></i>OpenKerja</a>
            <a href="#"><i class="fab fa-linkedin"></i>OpenKerja</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 OpenKerja. Semua Hak Dilindungi Undang-undang.</p>
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var modal = document.getElementById("loginModal");
    var loginBtn = document.getElementById("loginBtn");
    var closeBtn = document.querySelector(".close");

    loginBtn.onclick = function (event) {
        event.preventDefault();
        modal.style.display = "block";
    };

    closeBtn.onclick = function () {
        modal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});
</script>
</body>
</html>
