<?php
require '../config.php';

session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];

    // Validate the code entered by the user
    if (!empty($code)) {
        // Check if the code exists in the database and is associated with an unverified user
        $stmt = $pdo->prepare("SELECT id, verification_code FROM raiders WHERE verification_code = ? AND is_verified = 0");
        $stmt->execute([$code]);

        // Check if the code matches any user
        if ($stmt->rowCount() > 0) {
            // Update user to set as verified
            $customer = $stmt->fetch();
            $update_stmt = $pdo->prepare("UPDATE raiders SET is_verified = 1, verification_code = NULL WHERE id = ?");
            $update_stmt->execute([$customer['id']]);

            if ($update_stmt->rowCount() > 0) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Email verified successfully!'];
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update the verification status!'];
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid verification code!'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Please enter the verification code.'];
    }

    header("Location: verify.php");
    exit();
}

?>

<?php include '../header.php'; ?>

<div class="p-4">
    <img src="../logo.png" class="w-32 mx-auto mb-4" alt="Logo" />
    <div class="max-w-md mx-auto bg-white shadow rounded p-4">
        <?php include '../alert.php'; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="code" class="block mb-2 text-sm font-medium text-gray-900">Verification Code</label>
                <input type="number" id="code" name="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Verify</button>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>
