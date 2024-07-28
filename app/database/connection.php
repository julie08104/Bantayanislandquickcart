<?php
$servername = "127.0.0.1";
$username = "u510162695_ample";
$password = "1Ample_database";
$dbname = "u510162695_ample";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set charset
    $pdo->exec("set names utf8");
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>