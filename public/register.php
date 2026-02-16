<?php
session_start();
require 'diary.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    try {
        $stmt->execute([$email, $password]);
        $msg = "Registration successful! <a href='login.php'>Login here</a>";
    } catch (PDOException $e) {
        $msg = "Email already exists!";
    }
}
?>