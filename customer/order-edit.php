<?php
    require '../config.php';
    require '../auth_check.php';

    $user_id = $_SESSION['user_id'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : '';
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ? AND status = 'pending'");
    $stmt->execute([$id, $user_id]);
    $order = $stmt->fetch();
    
    if (!$order) {
        header("Location: order-list.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $instruction = $_POST['instruction'];
        
        $stmt = $pdo->prepare("UPDATE orders SET instruction = ? WHERE id = ? AND customer_id = ? AND status = 'pending'");
        if ($stmt->execute([$instruction, $id, $user_id])) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Order updated successfully.'];
            header("Location: order-list.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Order update failed. Please try again.'];
            header("Location: order-edit.php?id=" . $id);
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
            <h1 class="text-2xl">Edit Order</h1>
        </div>
        <div class="max-w-md">
            <form method="POST">
                <div class="mb-4">
                    <label for="instruction" class="block mb-2 text-sm font-medium text-gray-900">Instruction</label>
                    <textarea id="instruction" name="instruction" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required><?php echo $order['instruction']; ?></textarea>
                </div>
                <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Update</button>
                <a href="order-list.php" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
