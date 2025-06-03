<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']); // Nama diambil dari form
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validasi password cocok
    if ($password !== $confirm) {
        $_SESSION['login_error'] = "Password tidak cocok.";
        header("Location: home.php");
        exit;
    }

    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['login_error'] = "Email sudah digunakan.";
        header("Location: home.php");
        exit;
    }

    // Hash password dan siapkan foto default
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $default_photo = 'default.png'; // Letakkan file ini di folder `uploads/`

    // Simpan user baru
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $default_photo);

    if ($stmt->execute()) {
        // Login otomatis setelah registrasi
        $_SESSION['user'] = [
            'name' => $name,
            'email' => $email,
            'photo' => $default_photo
        ];
        header("Location: home.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Terjadi kesalahan saat mendaftar.";
        header("Location: home.php");
        exit;
    }
}
?>