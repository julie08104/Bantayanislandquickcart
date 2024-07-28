<?php
// Initialize session if not already done
session_start();

// Include necessary initialization or configuration files
require_once 'app/init.php';

// Check if token is provided and valid
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // Verify token validity (e.g., check against database)
    // Example: $user = find_user_by_token($token);
    // If token is valid, allow user to reset password
    // Otherwise, handle invalid token scenario
} else {
    // Handle case where token is not provided
    // Redirect to appropriate page or display error message
}
?>

<!DOCTYPE HTML>
<html lang="en-us">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" type="text/css" />
    <!-- Additional styles if needed -->
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Reset Password</h3>
                        <!-- Password reset form -->
                        <form action="reset_password.php" method="post">
                            <div class="form-group">
                                <label for="new_password">Enter your new password:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm your new password:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
