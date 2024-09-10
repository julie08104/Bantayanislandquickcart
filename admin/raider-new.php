<?php
    require '../config.php';
    require '../auth_check.php';

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
            header("Location: raider-new.php");
            exit();
        }

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO raiders (firstname, lastname, phone, address, vehicle_type, vehicle_number, email, password_hash, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$firstname, $lastname, $phone, $address, $vehicle_type, $vehicle_number, $email, $password_hash, $verification_code])) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Raider created successfully.'];
            header("Location: raider-list.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Raider creation failed. Please try again.'];
            header("Location: raider-new.php");
            exit();
        }
    }
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="bg-white shadow rounded p-4">
        <?php include '../alert.php'; ?>
        <div class="flex items-center justify-between gap-4 mb-4">
            <h1 class="text-2xl">New Raider</h1>
        </div>
        <div class="max-w-md">
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
                <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Save</button>
                <a href="raider-list.php" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>