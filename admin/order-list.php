<?php
    require '../config.php';
    require '../auth_check.php';
    
    $sql = '
        SELECT
            o.id AS order_id,
            o.instruction,
            o.status,
            o.created_at,
            r.id AS raider_id,
            CONCAT(r.firstname, " ", r.lastname) AS raider_fullname,
            r.phone AS raider_phone,
            r.vehicle_type,
            r.vehicle_number,
            rev.id AS review_id,
            rev.rating AS review_rating,
            rev.comment AS review_comment,
            rev.created_at AS review_created_at,
            c.id AS customer_id,
            CONCAT(c.firstname, " ", c.lastname) AS customer_fullname,
            c.phone AS customer_phone,
            c.address AS customer_address
        FROM
            orders o
        LEFT JOIN
            assignments a ON o.id = a.order_id
        LEFT JOIN
            raiders r ON a.raider_id = r.id
        LEFT JOIN
            reviews rev ON o.id = rev.order_id
        LEFT JOIN
            customers c ON o.customer_id = c.id
        ORDER BY
            o.created_at DESC
    ';

    $stmt = $pdo->query($sql);
    $orders = $stmt->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'];
        $raider_id = $_POST['raider_id'];

        if($raider_id && $order_id){
            // Check if the order is already assigned to a raider
            $stmt = $pdo->prepare('SELECT id FROM assignments WHERE order_id = ?');
            $stmt->execute([$order_id]);
            $existingAssignment = $stmt->fetch();

            // Update order status
            $stmt = $pdo->prepare('UPDATE orders SET status = "assigned" WHERE id = ?');
            $stmt->execute([$order_id]);
        
            if ($existingAssignment) {
                // Update existing assignment
                $stmt = $pdo->prepare('UPDATE assignments SET raider_id = ?, assigned_at = NOW() WHERE order_id = ?');
                $stmt->execute([$raider_id, $order_id]);
                
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Order assignment updated successfully!'];
            } else {
                // Insert new assignment
                $stmt = $pdo->prepare('INSERT INTO assignments (order_id, raider_id, assigned_at) VALUES (?, ?, NOW())');
                $stmt->execute([$order_id, $raider_id]);
        
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Order assigned successfully!'];
            }

            header("Location: order-list.php");
            exit();
        }else{
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Order assign failed!'];
            header("Location: order-list.php");
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
                    <?php if ($order['customer_id']): ?>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Customer: </p>
                            <div class="bg-gray-100 p-2 rounded">
                                <p class="text-sm"><?php echo $order['customer_fullname'] ?></p>
                                <p class="text-sm"><?php echo $order['customer_address'] ?></p>
                                <p class="text-sm"><?php echo $order['customer_phone'] ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Instruction: </p>
                        <p class="bg-gray-100 p-2 rounded"><?php echo nl2br(htmlspecialchars($order['instruction'])); ?></p>
                    </div>

                    <?php if($order['status'] == 'pending' || $order['status'] == 'assigned'): ?>
                        <form id="assignOrderForm" method="post">
                            <label for="raider_id" class="block mb-1 text-sm font-medium text-gray-900">Assign Raider:</label>
                            <select id="raider_id" name="raider_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">Select Raider</option>
                                <?php
                                    $stmt = $pdo->query("SELECT id, CONCAT(firstname, ' ', lastname) AS fullname FROM raiders");
                                    $raiders = $stmt->fetchAll();
                                    foreach ($raiders as $raider):
                                ?>
                                    <option <?php echo $order['raider_id'] == $raider['id'] ? 'selected' : ''?> value="<?php echo htmlspecialchars($raider['id']); ?>"><?php echo htmlspecialchars($raider['fullname']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                        </form>
                    <?php else: ?>
                        <?php if ($order['raider_id']): ?>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Assign Raider: </p>
                                <div class="bg-gray-100 p-2 rounded">
                                        <p class="text-sm"><?php echo $order['raider_fullname'] ?></p>
                                        <p class="text-sm"><?php echo $order['raider_phone'] ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($order['status'] == 'completed' && $order['review_id']): ?>
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

<script>
    document.getElementById('raider_id').addEventListener('change', function() {
        document.getElementById('assignOrderForm').submit();
    });
</script>

<?php include '../footer.php'; ?>