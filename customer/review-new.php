<?php
    $page_type='customer';
    require '../config.php';
    require '../auth_check.php';

    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : '';
    $user_id = $_SESSION['user_id'];

    $sql = '
        SELECT
            o.id AS order_id,
            o.instruction,
            o.total_amount,
            o.delivery_fee,
            o.status,
            o.created_at,
            r.id AS raider_id,
            CONCAT(r.firstname, " ", r.lastname) AS raider_fullname,
            r.phone,
            r.vehicle_type,
            r.vehicle_number,
            rev.id AS review_id,
            rev.rating AS review_rating,
            rev.comment AS review_comment,
            rev.created_at AS review_created_at
        FROM
            orders o
        LEFT JOIN
            assignments a ON o.id = a.order_id
        LEFT JOIN
            raiders r ON a.raider_id = r.id
        LEFT JOIN
            reviews rev ON o.id = rev.order_id
        WHERE
            o.customer_id = ?
        AND o.id = ?
        AND o.status = "completed"
    ';
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $order_id]);
    $order = $stmt->fetch();

    if(!$order){
        header("Location: order-list.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        // Validate input
        if ($rating < 1 || $rating > 5) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid rating.'];
            header("Location: review-new.php?order_id=".$order_id);
            exit();
        }
    
        // Insert review into the database
        $stmt = $pdo->prepare("INSERT INTO reviews (order_id, customer_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        if($stmt->execute([$order_id, $user_id, $rating, $comment])){
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Review submitted successfully!'];
            header("Location: order-list.php");
            exit();
        }else{
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Oops! Something went wrong!'];
            header("Location: review-new.php?order_id=".$order_id);
            exit();
        }
    }
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="bg-white shadow rounded p-4 space-y-4">
        <?php include '../alert.php'; ?>
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl">Review Order</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST">
                <div class="mb-4">
                    <label for="rating" class="block mb-2 text-sm font-medium text-gray-900">Rating:</label>
                    <select id="rating" name="rating" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="">Select Rating</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> Star(s)</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block mb-2 text-sm font-medium text-gray-900">Comment:</label>
                    <textarea id="comment" name="comment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Submit Review</button>
                <a href="order-list.php" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Cancel</a>
            </form>

            <!-- <div class="border rounded p-4 space-y-4 mb-4">
                <div class="flex items-start justify-between flex-wrap">
                    <div>
                        <p class="text-lg font-bold">Order ID: <?php echo htmlspecialchars($order['order_id']); ?></p>
                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($order['created_at']); ?></p>
                    </div>
                    <span class="text-xs bg-gray-100 rounded text-center uppercase py-1 px-4"><?php echo htmlspecialchars($order['status']); ?></span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Instruction: </p>
                    <p class="bg-gray-100 p-2 rounded"><?php echo nl2br(htmlspecialchars($order['instruction'])); ?></p>
                </div>
                <?php if($order['status'] != 'pending'): ?>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Assign Raider: </p>
                        <div class="bg-gray-100 p-2 rounded">
                            <p class="text-sm"><?php echo $order['raider_fullname'] ?></p>
                            <p class="text-sm"><?php echo $order['phone'] ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                <div>
                    <p>SubTotal: <?php echo $order['total_amount'] ?></p>
                    <p>Delivery Fee: <?php echo $order['delivery_fee'] ?></p>
                    <p class="font-bold">Total: <?php echo $order['total_amount'] + $order['delivery_fee'] ?></p>
                </div>
            </div> -->
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>