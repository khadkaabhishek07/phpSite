<?php
$host = "mysql.us.stackcp.com";
$port = 39498;
$dbname = "portfolio-35303539791a";
$username = "betatester";
$password = "Sthpi9@9999";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
