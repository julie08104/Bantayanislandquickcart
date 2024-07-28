<?php
session_start();
require_once '../init.php';

// Initialize User object with PDO
$Ouser = new User($pdo);

if ($_SERVER['127.0.0.1'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Attempt to login
    $Ouser->login($username, $password);
}
?>

