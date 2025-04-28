<?php
// db.php
$host = 'localhost';
$dbname = 'papeterie_20250427';
$username = 'root'; // Change this to your database username
$password = 'root'; // Change this to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
