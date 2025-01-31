<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Redirect to login page if not authenticated
    header('Location: login.php');
    exit;
}
require 'database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM internship_diary WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>

