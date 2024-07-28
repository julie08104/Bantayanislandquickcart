<?php
session_start();
require_once 'app/init.php'; // Ensure this file initializes PDO

// Initialize PDO
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u510162695_ample', 'u510162695_ample', '1Ample_database');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Initialize User object with PDO
$Ouser = new User($pdo);

// Check if the user is already logged in, redirect to index.php if true
if ($Ouser->is_login() != false) {
    header("location:index.php");
    exit();
}

// Initialize variables for form validation and error handling
$username = '';
$email = '';
$password = '';
$first_name = '';
$last_name = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!$email) {
        $errors['email'] = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    // Validate first name
    if (empty($first_name)) {
        $errors['first_name'] = "First Name is required";
    }

    // Validate last name
    if (empty($last_name)) {
        $errors['last_name'] = "Last Name is required";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database using PDO
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name) VALUES (:username, :email, :password, :first_name, :last_name)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);

        if ($stmt->execute()) {
            $_SESSION['registration_success'] = true;
            header("Location: login.php?registration_success=true");
            exit();
        } else {
            $_SESSION['status'] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="en-us">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" type="text/css" />
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Create Account</h3>
                        <form action="register.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                                <?php if (isset($errors['username'])) : ?>
                                    <div class="text-danger"><?php echo $errors['username']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                                <?php if (isset($errors['email'])) : ?>
                                    <div class="text-danger"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                                <?php if (isset($errors['password'])) : ?>
                                    <div class="text-danger"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($first_name); ?>" required>
                                <?php if (isset($errors['first_name'])) : ?>
                                    <div class="text-danger"><?php echo $errors['first_name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($last_name); ?>" required>
                                <?php if (isset($errors['last_name'])) : ?>
                                    <div class="text-danger"><?php echo $errors['last_name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
