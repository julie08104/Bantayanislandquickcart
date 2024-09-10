<?php
    require '../config.php';

    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    if (isset($_GET['code'])) {
        $code = $_GET['code'];

        // Check verification code
        $stmt = $pdo->prepare("UPDATE customers SET is_verified = 1, verification_code = NULL WHERE verification_code = ?");
        if ($stmt->execute([$code])) {
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Email verified successfully!'];
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid verification code!'];
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Verification failed!'];
        }
    }

    header("Location: login.php");
    exit();
?>
