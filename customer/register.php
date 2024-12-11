<?php
    require '../config.php';

    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

         // Check if Terms and Conditions checkbox is checked
        if (!isset($_POST['terms'])) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'You must accept the terms and conditions to register.'];
            header("Location: register.php");
            exit();
        }
        
        // Password strength validation
        if (!preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[\W_]/', $password) || 
            strlen($password) < 8) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.'];
            header("Location: register.php");
            exit;
        }
    
        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Passwords do not match!'];
            header("Location: register.php");
            exit;
        }
    
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $verification_code = md5(uniqid("yourrandomstring", true));
    
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Email already registered!'];
            header("Location: register.php");
            exit;
        }
    
        // Insert user
        $stmt = $pdo->prepare("INSERT INTO customers (firstname, lastname, phone, address, email, password_hash, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$firstname, $lastname, $phone, $address, $email, $password_hash, $verification_code])) {
            // TODO: Send verification email
            $verification_link = "https://bantayanquickcart.com/customer/verify.php";
            $verification_message = "Hello,\n\nTo verify your email, please enter the following verification code on the verification page:\n\n$verification_code\n\nClick this link to go to the verification page: $verification_link\n\nThank you!";
            mail($email, "Verify your email", $verification_message, "From: bantayanquickcart@gmail.com");
                
            // $verification_link = "https://bantayanquickcart.com/customer/verify.php?code=$verification_code";
            // mail($email, "Verify your email", "Click this link to verify your email: $verification_link", "From: bantayanquickcart@gmail.com");
    
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Registration successful! Check your email to verify your account.'];
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Registration failed!'];
            header("Location: register.php");
            exit;
        }
    }    
?>

<?php include '../header.php'; ?>

<div class="p-4">
    <img src="../logo.png" class="w-32 mx-auto mb-4" alt="Logo" />
    <div class="max-w-md mx-auto bg-white shadow rounded p-4">
    <?php include '../alert.php'; ?>
        <form method="POST" id="register-form">
            <div class="grid grid-cols-1 grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="firstname" class="block mb-2 text-sm font-medium text-gray-900">Firstname</label>
                    <input type="text" id="firstname" name="firstname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
                <div>
                    <label for="lastname" class="block mb-2 text-sm font-medium text-gray-900">Lastname</label>
                    <input type="text" id="lastname" name="lastname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
            </div>
            <div class="mb-4">
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Phone Number</label>
                <input type="number" id="phone" name="phone" maxlength="11" oninput="validatePhoneInput(event)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                <input type="text" id="address" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label class="flex items-start">
                    <input type="checkbox" id="terms" name="terms" class="mr-2 mt-1" required />
                    <span class="text-sm text-gray-900">
                        By using Bantayan Island QuickCart, you confirm that you legally agree to these <a href="/terms_and_conditions.php" class="text-blue-500 hover:underline">Terms and Conditions.</a> If you do not meet this requirement, please do not use the platform.
                    </span>
                </label>
            </div>
            <button
                data-sitekey="6LeWO1YqAAAAALCrSqRbOX0mYKiSSyWWDe65aYB_" 
                data-callback='onSubmit' 
                data-action='submit'
                class="g-recaptcha mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">
                Submit
            </button>
            <!-- <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Sign Up</button> -->
            <p class="text-sm text-center">
                <span>You already have an account? </span>
                <a href="login.php" class="text-blue-500 hover:underline">Sign In</a>
            </p>
        </form>
    </div>
</div>

<script>
   function onSubmit(token) {
     document.getElementById("register-form").submit();
   }
</script>

<?php include '../footer.php'; ?>