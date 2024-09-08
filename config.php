<?php
$dsn = 'mysql:host=u510162695_ample;dbname=u510162695_ample'; // live
// $dsn = 'mysql:host=localhost;dbname=mcc'; // develop
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
