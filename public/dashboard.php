<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notes = $stmt->fetchAll();
?>