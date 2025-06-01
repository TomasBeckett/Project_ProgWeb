<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/detail.css">
    <title>Detail Lowongan</title>
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <table>
                    <tr>
                        <td>
                            <a href="http://127.0.0.1:5500/Lowongan.html#">
                                <img src="../Assets/Pic/Other/OPEN.png" width="150px" alt="Logo Lowongan">
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
        </div>
        
        <div id="loginModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Login</h2>
                <form>
                    <input type="text" id="username" name="username" placeholder="Username">
                    <input type="password" id="password" name="password" placeholder="Password">
        
                    <button type="submit" class="button">Masuk</button>
                    <p>Belum punya akun? <a href="#">Registrasi</a></p>
                </form>
            </div>
        </div>  
    </header>

    <?php
    require 'db.php';

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
                <a href="/Lowongan.html" class="back-button">← Kembali ke Lowongan</a>

                <img src="<?= $lowongan['banner_img'] ?>" alt="Banner" class="banner">
                <img src="<?= $lowongan['logo_img'] ?>" alt="Logo Perusahaan" class="logo">
                <div class="job-title"><?= htmlspecialchars($lowongan['title']) ?></div>
                <div class="company-name"><?= htmlspecialchars($lowongan['perusahaan']) ?></div>
                <p>📍 <?= htmlspecialchars($lowongan['lokasi']) ?></p>
                <p>💻 <?= htmlspecialchars($lowongan['bidang']) ?></p>
                <p>⏳ <?= htmlspecialchars($lowongan['tipe']) ?></p>
                <p>💰 <?= htmlspecialchars($lowongan['gaji']) ?></p>
                <div class="buttons">
                    <button class="apply-btn">
                        <a href="/Pengajuan/form_lamaran.php?id=<?= $lowongan['id'] ?>">Buat Lamaran</a>
                    </button>
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
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById("loginModal");
            var loginBtn = document.getElementById("loginBtn"); 
            var closeBtn = document.querySelector(".close");
        
            loginBtn.onclick = function(event) {
                event.preventDefault(); 
                modal.style.display = "block";
            }
        
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }
        
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
</body>
</html>