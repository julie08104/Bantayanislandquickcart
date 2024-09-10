<?php
    require '../config.php';
    require '../auth_check.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $instruction = $_POST['instruction'];
        $id = $_SESSION['user_id'];

        $stmt = $pdo->prepare('INSERT INTO orders (customer_id, instruction, status, created_at) VALUES (?, ?, "pending", NOW())');
        if($stmt->execute([$id, $instruction])){
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Order created successfully.'];
            header("Location: order-list.php");
            exit();
        }else{
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Order creation failed. Please try again.'];
            header("Location: order-new.php");
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
            <h1 class="text-2xl">New Order</h1>
        </div>
        <div class="max-w-md">
            <form method="POST">
                <div class="mb-4">
                    <label for="instruction" class="block mb-2 text-sm font-medium text-gray-900">Instruction</label>
                    <textarea id="instruction" name="instruction" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>
                <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Save</button>
                <a href="raider-list.php" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>