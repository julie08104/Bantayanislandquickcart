<?php
// Define cooldown logic
function checkCooldown($session_var_prefix, $max_attempts = 3, $cooldown_time = 30) {
    // Initialize session variables if not set
    if (!isset($_SESSION[$session_var_prefix . '_attempts'])) {
        $_SESSION[$session_var_prefix . '_attempts'] = 0;
    }
    if (!isset($_SESSION[$session_var_prefix . '_last_failed_attempt'])) {
        $_SESSION[$session_var_prefix . '_last_failed_attempt'] = 0;
    }

    // Check if the user is in cooldown
    if ($_SESSION[$session_var_prefix . '_attempts'] >= $max_attempts && 
        (time() - $_SESSION[$session_var_prefix . '_last_failed_attempt']) < $cooldown_time) {
        return ['cooldown' => true, 'message' => 'Too many failed attempts. Please try again in ' . ($cooldown_time - (time() - $_SESSION[$session_var_prefix . '_last_failed_attempt'])) . ' seconds.'];
    }

    return ['cooldown' => false];
}

// Function to increment failed attempts and set last failed attempt time
function incrementFailedAttempts($session_var_prefix) {
    $_SESSION[$session_var_prefix . '_attempts']++;
    $_SESSION[$session_var_prefix . '_last_failed_attempt'] = time();
}

// Function to reset failed attempts after a successful action
function resetFailedAttempts($session_var_prefix) {
    $_SESSION[$session_var_prefix . '_attempts'] = 0;
}

?>
