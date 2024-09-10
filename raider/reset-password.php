<?php
    require '../config.php';

    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    if (!isset($_GET['code'])) {
        header("Location: forgot-password.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = $_POST['new_password'];
        $code = $_GET['code'];
    
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
    
        // Update password
        $stmt = $pdo->prepare("UPDATE raiders SET password_hash = ?, forgot_password_code = NULL WHERE forgot_password_code = ?");
        if ($stmt->execute([$password_hash, $code])) {
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Password reset successfully!'];
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid reset code!'];
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Password reset failed!'];
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
                <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900">New Password</label>
                <input type="password" id="new_password" name="new_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Reset Password</button>
            <p class="text-sm text-center">
                <a href="login.php" class="text-blue-500 hover:underline">Back to Sign In</a>
            </p>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>