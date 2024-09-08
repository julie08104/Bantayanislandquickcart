<?php
    require '../config.php';
    require '../auth_check.php';

    $id = $_SESSION['user_id'];

    $sql = '
        SELECT
            o.id AS order_id,
            o.instruction,
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
            a.raider_id = ?
        ORDER BY
            o.created_at DESC
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $orders = $stmt->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'];
        $stmt = $pdo->prepare('UPDATE orders SET status = "completed" WHERE id = ?');
        $stmt->execute([$order_id]);

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Order completed successfully!'];
        header("Location: order-list.php");
        exit();
    }
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="bg-white shadow rounded p-4 space-y-4">
        <?php include '../alert.php'; ?>
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl">Order List</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($orders as $order): ?>
                <div class="border rounded p-4 space-y-4">
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

                    <?php if($order['status'] != 'completed'): ?>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Complete Order</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($order['review_id']): ?>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Review: </p>
                            <div class="bg-gray-100 p-2 rounded">
                                <div class="flex items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-5 h-5 <?php echo $i <= $order['review_rating'] ? 'text-yellow-300' : 'text-gray-300'; ?>" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                        <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                                    </svg>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-xs text-gray-500 mb-2"><?php echo htmlspecialchars($order['review_created_at']); ?></p>
                                <p class="text-sm"><?php echo nl2br(htmlspecialchars($order['review_comment'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>