<?php
    require '../config.php';
    require '../auth_check.php';

    $user_id = $_SESSION['user_id'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : '';
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ? AND customer_id = ?");
    $stmt->execute([$id, $user_id]);
    $review = $stmt->fetch();
    
    if (!$review) {
        header("Location: order-list.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        if ($rating < 1 || $rating > 5) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid rating.'];
            header("Location: review-edit.php?id=".$id);
            exit();
        }
        
        $stmt = $pdo->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ? AND customer_id = ?");
        if ($stmt->execute([$rating, $comment, $id, $user_id])) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Order review updated successfully.'];
            header("Location: order-list.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Order review update failed. Please try again.'];
            header("Location: review-edit.php?id=" . $id);
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
            <h1 class="text-2xl">Edit Review Order</h1>
        </div>
        <div class="max-w-md">
            <form method="POST">
                <div class="mb-4">
                    <label for="rating" class="block mb-2 text-sm font-medium text-gray-900">Rating:</label>
                    <select id="rating" name="rating" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="">Select Rating</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $review['rating'] == $i ? 'selected' : '' ?>>
                                <?php echo $i; ?> Star(s)
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block mb-2 text-sm font-medium text-gray-900">Comment:</label>
                    <textarea id="comment" name="comment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required><?php echo $review['comment']; ?></textarea>
                </div>
                <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Update</button>
                <a href="order-list.php" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
