<?php
session_start();

// Include initialization file which sets up PDO connection
require_once 'app/init.php';

// Initialize User object with PDO
$Ouser = new User($pdo);

// Check if the user is already logged in, redirect to index.php if true
if ($Ouser->is_login()) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE HTML>
<html lang="en-us">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css" type='text/css' />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" type="text/css" />
    <title>Login</title>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .login-container {
            min-height: 100vh;
        }
        .login-box {
            max-width: 400px;
            width: 100%;
        }
        .login-logo img {
            max-width: 100%;
            height: auto;
        }
        .form-control.input {
            background-color: #f0f0f0;
        }
        .card {
            background-color: #dcdcdc; 
        }
        .create-account-btn {
            background-color: #5890FF; 
            color: #dcdcdc;
            border: none;
            padding: 10px;
            width: 100px; 
            margin-top: 10px; 
        }
        .create-account-btn:hover {
            background-color: #5890FF; /* Lighter shade on hover */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (isset($_GET['registration_success']) && $_GET['registration_success'] === 'true') : ?>
                Swal.fire({
                    title: 'Registration Successful!',
                    text: 'You can now log in.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.php'; // Redirect to login page
                });
            <?php endif; ?>
        });
    </script>
</head>
<body>
    
    <div class="container-fluid login-container d-flex align-items-center justify-content-center">

        <div class="login-box">
            <div class="card">

                <div class="card-body">
                    <div class="login-logo mb-4 text-center">
                       
                    </div>
                    <form action="app/action/login.php" method="post">
                        <?php 
                        if (isset($_SESSION['login_error'])) {
                            echo "<div class='alert alert-danger text-center'>".$_SESSION['login_error']."</div>";
                            unset($_SESSION['login_error']); // Clear the error after displaying
                        }
                        ?>
                        <div class="form-group">
                            <label class="mb-2 tag text-left"><strong>Username</strong>:</label>
                            <input type="text" name="username" placeholder="Enter your username" class="form-control input" required />
                        </div>
                        <div class="form-group">
                            <label class="mb-2 tag text-left"><strong>Password</strong>:</label>
                            <input type="password" name="password" placeholder="Enter your password" class="form-control input" required />
                        </div>
                        <div class="text-center">
                            <button type="submit" name="admin_login" class="btn btn-primary btn-block">Login</button>
                            <a href="password_reset.php" class="float-end">Forgot Password</a>
                            <br><br>
                            <a href="register.php" class="create-account-btn">Create Account</a> <!-- Link to the registration page -->
                        </div>
                    </form>
                    <div class="new-account mt-2 tag text-center">
                        <!-- Additional content can be added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
