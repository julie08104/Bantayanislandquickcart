<?php
    require '../config.php';

    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $verification_code = md5(uniqid("yourrandomstring", true));

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Email already registered!'];
            exit;
        }

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, verification_code) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $password_hash, $verification_code])) {
            // TODO: Send verification email
            $verification_link = "https://bantayanquickcart.com/admin/verify.php?code=$verification_code";
            mail($email, "Verify your email", "Click this link to verify your email: $verification_link", "From: bantayanquickcart@gmail.com");

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Registration successful! Check your email to verify your account.'];
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Registration failed!'];
        }
    }
?>

<?php include '../header.php'; ?>

<div class="p-4">
    <img src="../logo.png" class="w-32 mx-auto mb-4" alt="Logo" />
    <div class="max-w-md mx-auto bg-white shadow rounded p-4">
        <form method="POST">
            <div class="mb-4">
                <label for="f" class="block mb-2 text-sm font-medium text-gray-900">Fullname</label>
                <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Sign Up</button>
            <p class="text-sm text-center">
                <span>You already have an account? </span>
                <a href="login.php" class="text-blue-500 hover:underline">Sign In</a>
            </p>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>