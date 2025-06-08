<php session_start() ?>
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
                            <?php if (isset($_SESSION['user'])): ?>
                                <div class="user-info" style="position: relative; display: inline-block; font-family: sans-serif;">
                                    <div id="profileTrigger" style="cursor: pointer; margin-right:20px; padding: 8px 12px; background-color: #f0f0f0; border-radius: 6px; transition: background 0.2s;">
                                        <span><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Pengguna') ?></span>
                                    </div>
                                    <div id="profileDropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0px 4px 8px rgba(0,0,0,0.1); min-width: 120px; z-index: 10;">
                                        <form action="logout.php" method="post" style="margin: 0;">
                                            <button type="submit" style="padding: 10px 16px; width: 100%; background: none; border: none; text-align: left; font-size: 14px; cursor: pointer;">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            <?php else: ?>
                                <button class="button" id="loginBtn">Login</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>    
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
                    <!-- Redirect ke halaman saat ini -->
                    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <button type="submit" class="button">Masuk</button>
                    <p>Belum punya akun? <a href="#" id="toRegister">Registrasi</a></p>
                </form>
            </div>
                    
            <!-- Form Register -->
            <div id="registerForm" style="display: none;">
                <h2>Registrasi</h2>
                <form method="post" action="register_process.php">
                    <input type="text" name="name" required placeholder="Nama Lengkap">
                    <input type="email" name="email" required placeholder="Email">
                    <input type="password" name="password" required placeholder="Password">
                    <input type="password" name="confirm_password" required placeholder="Konfirmasi Password">
                    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <button type="submit" class="button">Daftar</button>
                    <p>Sudah punya akun? <a href="#" id="toLogin">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</header>