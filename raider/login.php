<?php
    require '../config.php';

    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id, password_hash, is_verified FROM raiders WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['is_verified']) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Please verify your email first.'];
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid credentials!'];
        }
    }
?>

<?php include '../header.php'; ?>

<div class="p-4">
    <img src="../logo.png" class="w-32 mx-auto mb-4" alt="Logo" />
    <div class="max-w-md mx-auto bg-white shadow rounded p-4">
        <?php include '../alert.php'; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                <a href="forgot-password.php" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
            </div>
            <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Sign In</button>
            <p class="text-sm text-center">
                <span>Don't have an account? </span>
                <a href="register.php" class="text-blue-500 hover:underline">Sign Up</a>
            </p>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>