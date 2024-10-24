<?php
    require '../config.php';
    require '../auth_check.php';

    $user_id = $_SESSION['user_id'];
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : '';

    $sql = '
        SELECT
            o.id AS order_id,
            o.address AS customer_address,
            o.latitude AS customer_latitude,
            o.longitude AS customer_longitude,
            o.instruction,
            o.delivery_fee,
            o.status,
            o.created_at,
            o.total_amount,
            c.id AS customer_id,
            CONCAT(c.firstname, " ", c.lastname) AS customer_fullname,
            c.phone AS customer_phone,
            s.name AS store_name,
            s.location AS store_location,
            s.latitude AS store_latitude,
            s.longitude AS store_longitude,
            r.id AS raider_id,
            CONCAT(r.firstname, " ", r.lastname) AS raider_fullname,
            r.phone AS raider_phone,
            r.vehicle_type,
            r.vehicle_number,
            rev.id AS review_id,
            rev.rating AS review_rating,
            rev.comment AS review_comment,
            rev.created_at AS review_created_at
        FROM 
            orders o
        LEFT JOIN
            customers c ON o.customer_id = c.id
        LEFT JOIN
            stores s ON o.store_id = s.id
        LEFT JOIN
            reviews rev ON o.id = rev.order_id
        LEFT JOIN
            assignments a ON o.id = a.order_id
        LEFT JOIN
            raiders r ON a.raider_id = r.id 
        WHERE 
            o.id = ? 
        AND 
            a.raider_id = ?
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();

    if (!$order) {
        header("Location: order-list.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];
        $total_amount = $_POST['total_amount'];
        // $delivery_fee = $_POST['delivery_fee'];
    
        $stmt = $pdo->prepare('UPDATE orders SET status = ?, total_amount = ? WHERE id = ?');
        $stmt->execute([$status, $total_amount, $order_id]);
    
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Order status is '.$status];
        header("Location: order-list.php");
        exit();
    } 
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64 relative">
    <div class="bg-white shadow rounded">
        <div id="map" class="w-full h-80"></div>

        <div class="p-4 space-y-4">
            <?php include '../alert.php'; ?>
            
            <div class="flex flex-wrap items-center justify-between flex-wrap">
                <div>
                    <p class="text-lg font-bold">Order ID: <?php echo htmlspecialchars($order['order_id']); ?></p>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($order['created_at']); ?></p>
                </div>
                <span class="text-xs bg-gray-100 rounded text-center uppercase py-1 px-4"><?php echo htmlspecialchars($order['status']); ?></span>
            </div>
            <hr />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-4">
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
                            <p><?php echo $order['customer_address']; ?></p>
                        </li>
                    </ol>
                    <div>
                        <p class="text-sm text-gray-500">Order Details</p>
                        <p><?php echo nl2br(htmlspecialchars($order['instruction'])); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Delivery Fee: </p>
                        <p><?php echo $order['delivery_fee'] ?></p>
                    </div>
                </div>
                <div class="space-y-4">
                    <?php if($order['status'] == 'assigned'): ?>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">In Progress</button>
                        </form>
                    <?php endif; ?>

                    <?php if($order['status'] == 'in_progress'): ?>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <!-- <input class="text-sm w-full rounded-md mb-2" type="number" name="total_amount" placeholder="Total Amount" required> -->
                            <!-- <input class="text-sm w-full rounded-md mb-2" type="number" name="delivery_fee" placeholder="Delivery Fee" required> -->
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Complete Order</button>
                        </form>
                    <?php endif; ?>
                    <div>
                        <p class="text-sm text-gray-500">Assign Raider: </p>
                        <?php if ($order['raider_id']): ?>
                            <p><?php echo $order['raider_fullname'] ?></p>
                            <p><?php echo $order['raider_phone'] ?></p>
                        <?php else: ?>
                            <p>N/A</p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Reviews: </p>
                        <?php if ($order['status'] == 'completed' && $order['review_id']): ?>
                            <div class="mt-1 bg-gray-100 p-2 rounded">
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
                        <?php else: ?>
                            <p>N/A</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const order = <?php echo json_encode($order); ?>;

    const customer_lat_lng = [order.customer_latitude, order.customer_longitude];
    const store_lat_lng = [order.store_latitude, order.store_longitude];

    const map = L.map('map').setView(customer_lat_lng, 13);
    const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const control = L.Routing.control({
        waypoints: [
            L.latLng(store_lat_lng[0], store_lat_lng[1]),
            L.latLng(customer_lat_lng[0], customer_lat_lng[1])
        ],
        routeWhileDragging: true,
        router: new L.Routing.GraphHopper('9348a4ee-0d54-4f8a-9f53-6484ac3387e8'),
    }).addTo(map);

    const storeIcon = L.icon({
        iconUrl: '../store.png', // Replace with the path to your custom icon image
        iconSize: [30, 40], // Size of the icon [width, height]
        iconAnchor: [15, 40], // Point of the icon which will correspond to marker's location
        popupAnchor: [0, -40] // Point from which the popup should open relative to the iconAnchor
    });
    const raiderIcon = L.icon({
        iconUrl: '../raider.png', // Replace with the path to your custom icon image
        iconSize: [30, 40], // Size of the icon [width, height]
        iconAnchor: [15, 40], // Point of the icon which will correspond to marker's location
        popupAnchor: [0, -40] // Point from which the popup should open relative to the iconAnchor
    });

    L.marker(store_lat_lng,{ icon: storeIcon }).addTo(map).bindPopup(`Store: ${order.store_location}`).openPopup();
    L.marker(customer_lat_lng).addTo(map).bindPopup(`Your Location: ${order.customer_address}`).openPopup();

    const raiderMarker = L.marker([0, 0],{ icon: raiderIcon }).addTo(map);

    function updateLocation(latitude, longitude) {
        fetch('php/update_raider_location.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `latitude=${latitude}&longitude=${longitude}&order_id=${order.order_id}&raider_id=${order.raider_id}`
        })
        .then(response => response.json())
        .then(data => {
            raiderMarker.setLatLng([latitude, longitude]);
        })
        .catch(error => console.error('Error:', error));
    }

    if (order.status == "in_progress" && navigator.geolocation) {
        navigator.geolocation.watchPosition(position => {
            const { latitude, longitude } = position.coords;
            updateLocation(latitude, longitude);
        });
    } else {
        console.log('Geolocation is not supported by this browser.');
    }
</script>

<?php include '../footer.php'; ?>