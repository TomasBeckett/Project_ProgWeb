<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $redirectTo = $_POST['redirect_to'] ?? 'home.php';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Simpan info user ke session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name']
            ];

            // Jika email mengandung @staff.com, ke pengelola.php
            if (strpos($email, '@staff.com') !== false) {
                header("Location: pengelola.php");
                exit;
            } else {
                // Selain staff, redirect ke halaman asal (bisa halaman detail, dll)
                header("Location: " . $redirectTo);
                exit;
            }
        } else {
            $_SESSION['login_error'] = "Password salah.";
            header("Location: " . $redirectTo);
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Email tidak ditemukan.";
        header("Location: " . $redirectTo);
        exit;
    }
}
?>
