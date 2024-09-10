<?php
    require '../config.php';

    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
    
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    
        if ($user) {
            $forgot_password_code = md5(uniqid("yourrandomstring", true));
    
            // Update forgot password code
            $stmt = $pdo->prepare("UPDATE customers SET forgot_password_code = ? WHERE email = ?");
            $stmt->execute([$forgot_password_code, $email]);
    
           // Send reset link
           $reset_link = "https://bantayanquickcart.com/admin/reset-password.php?code=$forgot_password_code";
           mail($email, "Reset your password", "Click this link to reset your password: $reset_link", "From: ardiederrayal06@gmail.com");
            
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Reset password link has been sent to your email.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Email not found!'];
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
            <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Reset Password</button>
            <p class="text-sm text-center">
                <a href="login.php" class="text-blue-500 hover:underline">Back to Sign In</a>
            </p>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>