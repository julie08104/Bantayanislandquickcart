<?php
    require '../config.php';
    require '../cooldown.php';

    session_start();

    // Check if user is already logged in
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    
    $cooldown = checkCooldown('admin_login');

    // Check if user is in cooldown
    if ($cooldown['cooldown']) {
        $_SESSION['message'] = ['type' => 'error', 'text' => $cooldown['message']];
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];
        
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id, password_hash, is_verified FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
        
            // Check password and user verification
            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['is_verified']) {
                    resetFailedAttempts('admin_login');

                    $stmt = $pdo->prepare("SELECT * FROM sessions WHERE user_id = ? AND user_type = ?");
                    $stmt->execute([$user['id'], 'admin']);
                    $user_session = $stmt->fetch();
                    if(!$user_session){
                        $session_token = bin2hex(random_bytes(32));
                        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, user_type, session_token) VALUES (?, 'admin', ?)");
                        if (!$stmt->execute([$user['id'], $session_token])) {
                            $_SESSION['message'] = ['type' => 'error', 'text' => "Error inserting session. Please try again."];
                        }
                        $_SESSION['session_token'] = $session_token;
                    }else{
                        $_SESSION['session_token'] = $user_session['session_token'];
                    }

                    $_SESSION['user_type'] ='admin';
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['firstname'].' '.$user['lastname'];
                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Please verify your email first.'];
                }
            } else {
                incrementFailedAttempts('admin_login');

                $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid credentials!'];
            }
        }
    }
?>

<?php include '../header.php'; ?>

<div class="p-4">
    <img src="../logo.png" class="w-32 mx-auto mb-4" alt="Logo" />
    <div class="max-w-md mx-auto bg-white shadow rounded p-4">
        <?php include '../alert.php'; ?>
        <form method="POST" id="login-form">
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                <a href="forgot-password.php" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
            </div>
            <button
                data-sitekey="6LeWO1YqAAAAALCrSqRbOX0mYKiSSyWWDe65aYB_" 
                data-callback='onSubmit' 
                data-action='submit'
                class="g-recaptcha mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">
                Submit
            </button>
        </form>
    </div>
</div>

<script>
    function onSubmit(token) {
        document.getElementById("login-form").submit();
    }
</script>

<?php include '../footer.php'; ?>
