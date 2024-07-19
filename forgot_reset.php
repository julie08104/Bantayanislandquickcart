<?php
require_once '../init.php';

if (isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if token exists in the database
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        $email = $reset['email'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);

        // Delete the token from the database
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);

        echo "Your password has been reset successfully.";
    } else {
        echo "Invalid token.";
    }
}
?>
