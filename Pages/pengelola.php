<?php
require 'db.php';
session_start();
$query = "SELECT * FROM lowongan ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/web.css?v=<? time() ;?>">
    <title>Lowongan Pekerjaan</title>
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

    <main>
        
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