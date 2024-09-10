<?php
//live
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
$username = 'u510162695_ample';
$password = '1Ample_database';

// develop
//$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
//$username = 'u510162695_ample';
//$password = '1Ample_database';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
