<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

try {
    $pdo = new PDO("mysql:host=;dbname=", '','');
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set charset
    $pdo->exec("set names utf8");
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
