<?php
session_start();
require 'db.php'; 
$query = "SELECT * FROM lowongan ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/web.css?v=<?= time(); ?>">
    <title>Lowongan Pekerjaan</title>
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <table>
                    <tr>
                        <td>
                            <a href="home.php">
                                <img src="../Assets/Pic/Other/OPEN.png" width="150px" alt="Logo Lowongan">
                            </a>
                        </td>
                        <td>
                            <a href="home.php">Kerja</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
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
            
            <!-- Form Login -->
            <div id="loginForm">
                <h2>Login</h2>
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                        <?= htmlspecialchars($_SESSION['login_error']) ?>
                    </div>
                    <script>
                        window.addEventListener('DOMContentLoaded', function () {
                            document.getElementById("loginModal").style.display = "block";
                        });
                    </script>
                <?php unset($_SESSION['login_error']); endif; ?>
                    
                <form method="post" action="login_process.php">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" class="button">Masuk</button>
                    <p>Belum punya akun? <a href="#" id="toRegister">Registrasi</a></p>
                </form>
            </div>
                    
            <!-- Form Register -->
            <div id="registerForm" style="display: none;">
                <h2>Registrasi</h2>
                <form method="post" action="register_process.php">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                    <button type="submit" class="button">Daftar</button>
                    <p>Sudah punya akun? <a href="#" id="toLogin">Login</a></p>
                </form>
            </div>
        </div>
    </div>


    </header>

    <main>
        <section class="section">
            <div class="search-section">
                <label for="search">Cari Lowongan:</label>
                <div class="search-container">
                    <input type="text" id="search" placeholder="Nama Perusahaan, Lokasi, dll">
                    <button class="search-btn">🔍</button>
                </div>
            </div>            
            <table class="center">
                <tr class="search-section2">
                    <td>
                        <label for="company">Perusahaan:</label>
                        <input type="text" id="company" placeholder="Nama Perusahaan"">
                    </td>
                    <td>
                        <label for="category">Kategori:</label>
                        <input type="text" id="category" placeholder="Kategori Pekerjaan">
                    </td>
                    <td>
                        <label for="location">Lokasi:</label>
                        <select name="location" id="location">
                            <option value="Pilih">Pilih</option>
                            <option value="Jakarta">Jakarta</option>
                            <option value="Tangerang">Tangerang</option>
                            <option value="Bandung">Bandung</option>
                            <option value="Riau">Riau</option>
                        </select>
                    </td>
                    <td>
                        <label for="job-type">Jenis:</label>
                        <select name="job-type" id="job-type">
                            <option value="Pilih">Pilih</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Remote">Remote</option>
                            <option value="Freelance">Freelance</option>
                        </select>
                    </td>
                    <td>
                        <label for="salary-range">Gaji:</label>
                        <select name="salary-range" id="salary-range">
                            <option value="Pilih">Pilih</option>
                            <option value="2 Jt">2 Jt</option>
                            <option value="4 Jt">4 Jt</option>
                            <option value="6 Jt">6 Jt</option>
                            <option value="8 Jt">8 Jt</option>
                            <option value="10 Jt">10 Jt</option>
                            <option value="15 Jt">15 Jt</option>
                            <option value="20 Jt">20 Jt</option>
                        </select>
                    </td>
                </tr>
            </table>
        </section>

        <div class="content-wrapper">
            <div class="sidebar">
                <div>
                    <h3>Filter Pekerjaan</h3>
    
                <label for="kategori">Kategori</label>
                <select id="kategori">
                    <option>Pilih Kategori</option>
                    <option>IT</option>
                    <option>Administrasi</option>
                    <option>Desain Grafis</option>
                    <option>Pemasaran</option>
                    <option>Pertanian</option>
                </select>
    
                <label for="jenis">Jenis Pekerjaan</label>
                <select id="jenis">
                    <option>Pilih Jenis</option>
                    <option>Full-time</option>
                    <option>Part-time</option>
                    <option>Remote</option>
                    <option>Freelance</option>
                </select>
    
                <label for="gaji">Rentang Gaji</label>
                <input type="text" id="gaji" placeholder="Contoh: 5.000.000 - 10.000.000">
    
                <label for="lokasi">Lokasi</label>
                <select id="lokasi">
                    <option value="Pilih Lokasi">Pilih Lokasi</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Tangerang">Tangerang</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Riau">Riau</option>
                </select>
    
                <button class="filter-btn">Terapkan Filter</button>
                </div>
        </div>
        <div class="job-list">
        <table class="table-job">
            <?php
            $count = 0;
            while ($row = $result->fetch_assoc()) {
                if ($count % 2 == 0) echo "<tr>"; 
            ?>
                <td class="job-listing">
                    <img src="<?= $row['logo_img'] ?>" alt="Logo Perusahaan">
                    <div class="content">
                        <h2><a href="detail.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h2>
                        <p><strong>Perusahaan:</strong> <?= htmlspecialchars($row['perusahaan']) ?></p>
                        <p><strong>Kategori:</strong> <?= htmlspecialchars($row['bidang']) ?></p>
                        <p><strong>Jenis Pekerjaan:</strong> <?= htmlspecialchars($row['tipe']) ?></p>
                        <p><strong>Gaji:</strong> Rp<?= htmlspecialchars($row['gaji']) ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
                        <a href="detail.php?id=<?= $row['id'] ?>">Lihat Detail</a>
                    </div>
                </td>
            <?php
                $count++;
                if ($count % 2 == 0) echo "</tr>"; 
            }
            if ($count % 2 != 0) echo "<td></td></tr>"; 
            ?>
        </table>
    </div>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <h2>Pencari Kerja</h2>
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
        
            var loginForm = document.getElementById("loginForm");
            var registerForm = document.getElementById("registerForm");
        
            var toRegister = document.getElementById("toRegister");
            var toLogin = document.getElementById("toLogin");
        
            // Buka modal login
            loginBtn.onclick = function (event) {
                event.preventDefault();
                modal.style.display = "block";
                loginForm.style.display = "block";
                registerForm.style.display = "none";
            };
        
            // Tutup modal
            closeBtn.onclick = function () {
                modal.style.display = "none";
            };
        
            // Klik di luar modal menutup
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        
            // Pindah ke form registrasi
            toRegister.onclick = function (e) {
                e.preventDefault();
                loginForm.style.display = "none";
                registerForm.style.display = "block";
            };
        
            // Pindah kembali ke form login
            toLogin.onclick = function (e) {
                e.preventDefault();
                registerForm.style.display = "none";
                loginForm.style.display = "block";
            };
        });
    </script>

</body>
</html>