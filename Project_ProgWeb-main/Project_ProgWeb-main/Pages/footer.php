<?php $userLoggedIn = isset($_SESSION['user']); ?>
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
            <h3>Follow Us!</h3>
            <a href="#"><i class="fab fa-facebook"></i>FB : @OpenKerja</a>
            <a href="#"><i class="fab fa-twitter"></i>Twitter : @OpenKerja</a>
            <a href="#"><i class="fab fa-instagram"></i>Instagram : @OpenKerja</a>
            <a href="#"><i class="fab fa-linkedin"></i>Linkedin : @OpenKerja</a>
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Modal login handling...
    // Dropdown logout handling
    const profileTrigger = document.getElementById("profileTrigger");
    const profileDropdown = document.getElementById("profileDropdown");
    if (profileTrigger && profileDropdown) {
        profileTrigger.addEventListener("click", function () {
            profileDropdown.style.display = profileDropdown.style.display === "block" ? "none" : "block";
        });
        // Klik di luar dropdown menutupnya
        window.addEventListener("click", function (event) {
            if (!profileTrigger.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.style.display = "none";
            }
        });
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const applyBtn = document.getElementById("applyBtn");
    const modal = document.getElementById("loginModal");
    const userLoggedIn = <?= json_encode($userLoggedIn) ?>;
    const lowonganId = <?= json_encode($lowongan['id']) ?>;

    applyBtn.addEventListener("click", function () {
        if (userLoggedIn) {
            // Redirect ke halaman pengajuan lamaran
            window.location.href = "pengajuan.php?id=" + lowonganId;
        } else {
            // Tampilkan modal login
            modal.style.display = "block";
            // Pastikan form login yang muncul
            document.getElementById("loginForm").style.display = "block";
            document.getElementById("registerForm").style.display = "none";
        }
    });
});
</script>


