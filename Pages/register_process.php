<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

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

    // Hash dan simpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['email'] = $email;
        header("Location: home.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Terjadi kesalahan saat mendaftar.";
        header("Location: home.php");
        exit;
    }
}
?>
