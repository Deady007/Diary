<?php
$host = 'sql208.infinityfree.com';
$dbname = 'if0_37657516_diary';
$username = 'if0_37657516';
$password = 'TMFGxMANSHA13';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
