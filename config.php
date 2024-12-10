<?php
//live
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
$username = 'u510162695_ample';
$password = '1Ample_database';

// develop
// $dsn = 'mysql:host=localhost;dbname=mcc';
// $username = 'root';
// $password = '';

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
