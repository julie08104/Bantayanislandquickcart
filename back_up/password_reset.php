<?php
// Initialize session if not already done
session_start();

// Check if user is already logged in and redirect if true
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Include necessary initialization or configuration files
require_once 'app/init.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission here
    // Example: Send password reset email or perform necessary actions
    // You would typically send an email with a reset link or display a success message
    // This example assumes you send an email with a reset link
    $email = $_POST['email']; // Assuming your form has an input field with name="email"

    // You can implement your logic here to generate a unique token and store it in the database
    // Example: $token = generate_unique_token();

    // For demonstration purposes, let's just simulate sending an email with a reset link
    $reset_link = "http://example.com/reset_password.php?token=" . urlencode($token);
    
    // In a real application, you would send an email with this link to the user's email address
    // Example: mail($email, "Password Reset", "Click this link to reset your password: $reset_link");

    // Redirect user to a page indicating that an email has been sent
    $_SESSION['reset_email_sent'] = true;
    header("Location: password_reset.php");
    exit;
}
?>

<!DOCTYPE HTML>
<html lang="en-us">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" type="text/css" />
    <!-- Additional styles if needed -->
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Forgot Password</h3>
                        <?php if (isset($_SESSION['reset_email_sent']) && $_SESSION['reset_email_sent']) : ?>
                            <div class="alert alert-success" role="alert">
                                An email with instructions to reset your password has been sent to your email address.
                            </div>
                            <?php unset($_SESSION['reset_email_sent']); ?>
                        <?php else : ?>
                            <form action="password_reset.php" method="post">
                                <div class="form-group">
                                    <label for="email">Enter your email address:</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
