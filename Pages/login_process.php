<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'email' => $user['email'],
                'name' => $user['name'], 
                'photo' => $user['photo'] ?? 'default.png' 
            ];

            // Redirect berdasarkan role
            if (str_ends_with($email, '@staff.com')) {
                header("Location: pengelola.php");
            } else {
                header("Location: home.php");
            }
            exit;
        } else {
            $_SESSION['login_error'] = "Password salah.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    } else {
        $_SESSION['login_error'] = "Email tidak terdaftar.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}
?>
