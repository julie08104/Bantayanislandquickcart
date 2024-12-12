<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    // Delete all sessions for this user to log out from all devices
    $stmt = $pdo->prepare("DELETE FROM sessions WHERE user_id = ? AND user_type = ?");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_type']]);

    // Destroy the current session
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
