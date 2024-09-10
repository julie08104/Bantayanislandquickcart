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
        $vehicle_type = $_POST['vehicle_type'];
        $vehicle_number = $_POST['vehicle_number'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $verification_code = md5(uniqid("yourrandomstring", true));

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM raiders WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Email already registered!'];
            exit;
        }

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO raiders (firstname, lastname, phone, address, vehicle_type, vehicle_number, email, password_hash, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$firstname, $lastname, $phone, $address, $vehicle_type, $vehicle_number, $email, $password_hash, $verification_code])) {
            // TODO: Send verification email
            $verification_link = "http://localhost/mcc/admin/verify.php?code=$verification_code";
            mail($email, "Verify your email", "Click this link to verify your email: $verification_link", "From: ardiederrayal06@gmail.com");

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
                <input type="number" id="phone" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="mb-4">
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                <input type="text" id="address" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="grid grid-cols-1 grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="vehicle_type" class="block mb-2 text-sm font-medium text-gray-900">Vehicle Type</label>
                    <input type="text" id="vehicle_type" name="vehicle_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
                <div>
                    <label for="vehicle_number" class="block mb-2 text-sm font-medium text-gray-900">Vehicle Number</label>
                    <input type="text" id="vehicle_number" name="vehicle_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
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