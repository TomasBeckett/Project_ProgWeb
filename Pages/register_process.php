<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']); // Nama diambil dari form
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $redirectTo = $_POST['redirect_to'] ?? 'home.php';

    // Validasi password cocok
    if ($password !== $confirm) {
        $_SESSION['login_error'] = "Password tidak cocok.";
        header("Location: $redirectTo");
        exit;
    }

    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['login_error'] = "Email sudah digunakan.";
        header("Location: $redirectTo");
        exit;
    }

    // Hash password dan siapkan foto default
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $default_photo = 'default.png'; // Pastikan file ini ada di folder uploads/

    // Simpan user baru
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $default_photo);

    if ($stmt->execute()) {
        // Ambil id user yang baru disimpan
        $user_id = $conn->insert_id;

        // Login otomatis setelah registrasi
        $_SESSION['user'] = [
            'id' => $user_id,
            'name' => $name,
            'email' => $email,
            'photo' => $default_photo
        ];

        // Jika email staff, redirect ke pengelola.php
        if (strpos($email, '@staff.com') !== false) {
            header("Location: pengelola.php");
            exit;
        } else {
            // Selain staff, redirect ke halaman asal
            header("Location: $redirectTo");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Terjadi kesalahan saat mendaftar.";
        header("Location: $redirectTo");
        exit;
    }
} else {
    header("Location: home.php");
    exit;
}
?>
