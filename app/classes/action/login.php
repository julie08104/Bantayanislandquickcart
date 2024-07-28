<?php
session_start();
require_once '../init.php';

// Initialize User object with PDO
$Ouser = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Attempt to login
    $Ouser->login($username, $password);
}
?>
