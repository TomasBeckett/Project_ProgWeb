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
                                <div class="user-info" style="position: relative;">
                                    <div id="profileTrigger" style="cursor: pointer; display: flex; align-items: center;">
                                        <img src="uploads/<?= htmlspecialchars($_SESSION['user']['photo']) ?>" alt="Foto Profil" class="profile-img" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <span style="margin-left: 8px;"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                                    </div>
                                    <div id="profileDropdown" style="display: none; position: absolute; top: 45px; right: 0; background: white; border: 1px solid #ccc; box-shadow: 0px 2px 6px rgba(0,0,0,0.2); z-index: 10;">
                                        <form action="logout.php" method="post">
                                            <button type="submit" style="padding: 10px 20px; border: none; background: none; width: 100%; text-align: left;">Logout</button>
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
                    <button type="submit" class="button">Daftar</button>
                    <p>Sudah punya akun? <a href="#" id="toLogin">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</header>