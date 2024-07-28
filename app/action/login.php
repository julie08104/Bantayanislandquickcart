<?php
session_start();
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $Ouser = new User($pdo);
    if ($Ouser->login($username, $password)) {
        $_SESSION['user_id'] = $Ouser->get_user_id();
        header("Location: ../../index.php");
        exit();
    } else {
        $_SESSION['login_error'] = 'Invalid username or password';
        header("Location: ../../login.php");
        exit();
    }
}
?>
