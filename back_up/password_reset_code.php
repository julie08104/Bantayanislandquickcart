<?php
session_start();
include('../init.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../../vendor/autoload.php';

function send_password_reset($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'almohallasjulieann08@gmail.com'; // Your Gmail address
        $mail->Password = '***'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Sender and recipient
        $mail->setFrom('your_email@gmail.com', 'Your Name');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Reset Password Notification";
        $mail->Body = "Click the following link to reset your password: <a href='https://example.com/reset_password.php?token=$token'>Reset Password</a>";

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['password_reset_code'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Generate a secure token

    // Check if the email exists in the database
    $stmt = $con->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        // Update the user's password reset token
        $update = $con->prepare("UPDATE users SET reset_token = :token WHERE email = :email");
        $update->execute(['token' => $token, 'email' => $email]);

        if (send_password_reset($email, $token)) {
            $_SESSION['status'] = "We emailed you a password reset link.";
            header("Location: ../../password_reset.php");
        } else {
            $_SESSION['status'] = "Error sending email. Please try again later.";
            header("Location: ../../password_reset.php");
        }
    } else {
        $_SESSION['status'] = "No Email Found in the database.";
        header("Location: ../../password_reset.php");
    }
}
?>
