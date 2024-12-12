<?php
require 'config.php';

$session_timeout = 30 * 60; // 30 minutes in seconds

// Check if the session is still valid and not expired
if (isset($_SESSION['user_id']) && isset($_SESSION['session_token'])) {
    // Validate the session token in the database
    $stmt = $pdo->prepare("SELECT id FROM sessions WHERE user_id = ? AND session_token = ?");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['session_token']]);
    $session = $stmt->fetch();

    if (!$session) {
        // Invalid session, log out the user
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Check for session expiration (30 minutes of inactivity)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
        // Session expired due to inactivity, log out the user
        session_destroy();
        header("Location: login.php?expired=true"); // Optionally, add an expired parameter to the URL
        exit();
    }

    // Update the last activity timestamp
    $_SESSION['last_activity'] = time();
} else {
    // No session or invalid session
    header("Location: login.php");
    exit();
}
?>
