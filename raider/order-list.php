<?php
    require '../config.php';
    require '../auth_check.php';

    $id = $_SESSION['user_id'];

    $sql = '
        SELECT
           o.id AS order_id,
            o.address,
            o.instruction,
            o.total_amount,
            o.delivery_fee,
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
            c.address AS customer_address,
            s.name AS store_name,
            s.location AS store_location
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
        LEFT JOIN
            stores s ON o.store_id = s.id
        WHERE
            a.raider_id = ?
        ORDER BY
            o.created_at DESC
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $orders = $stmt->fetchAll();   
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div id="print" class="hidden">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Customer</th>
                        <th scope="col" class="px-6 py-3">Store</th>
                        <th scope="col" class="px-6 py-3">Order Details</th>
                        <th scope="col" class="px-6 py-3">Delivery Fee</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="bg-white border-b">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo $order['customer_fullname'] ?></td>
                            <td class="px-6 py-4"><?php echo $order['store_name'] ?></td>
                            <td class="px-6 py-4"><?php echo $order['instruction'] ?></td>
                            <td class="px-6 py-4"><?php echo $order['delivery_fee'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow rounded p-4 space-y-4">
        <?php include '../alert.php'; ?>
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl">Order List</h1>
            <button class="no-print text-sm px-4 py-2 border rounded" onclick="printPage()">Print</button>
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
                    <ol class="relative border-s border-gray-200 space-y-6">                  
                        <li class="ms-4">
                            <div class="absolute w-3 h-3 bg-white rounded-full mt-1.5 -start-1.5 border-2 border-red-500"></div>
                            <p><?php echo $order['store_name']; ?></p>
                            <p><?php echo $order['store_location']; ?></p>
                        </li>
                        <li class="ms-4">
                            <div class="absolute w-3 h-3 bg-white rounded-full mt-1.5 -start-1.5 border-2 border-green-500"></div>
                            <p><?php echo $order['customer_fullname']; ?></p>
                            <p><?php echo $order['customer_phone']; ?></p>
                            <p><?php echo $order['address']; ?></p>
                        </li>
                    </ol>
                    <a href="order-view.php?order_id=<?php echo $order['order_id'] ?>" class="block w-full text-center text-sm bg-blue-500 text-white rounded-md p-2">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
        function printPage() {
            var content = document.getElementById('print').innerHTML;
            var originalContent = document.body.innerHTML;

            // Set up the content to print in a new window
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title><link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" /></head><body>');
            printWindow.document.write('<div>' + content + '</div>');
            printWindow.document.write('</body></html>');

            // Close the document and print
            printWindow.document.close();
            printWindow.print();
        }
    </script>
<?php include '../footer.php'; ?>