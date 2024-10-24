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
        $address = $_POST['address'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $store_id = $_POST['store_id'];
        $instruction = $_POST['instruction'];
        $delivery_fee = $_POST['delivery_fee'];

        $stmt = $pdo->prepare("UPDATE orders SET address = ?, latitude = ?, longitude = ?, store_id = ?, instruction = ?, delivery_fee = ? WHERE id = ? AND customer_id = ? AND status = 'pending'");
        if ($stmt->execute([$address, $latitude, $longitude, $store_id, $instruction, $delivery_fee, $id, $user_id])) {
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
            <h1 class="text-2xl">New Order</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST">
                <div class="mb-4">
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Search Place</label>
                    <div class="flex items-start gap-2">
                        <div class="flex-1">
                            <input type="search" id="search" placeholder="Search for places..." class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                            <div id="suggestions" class="divide-y max-h-40 overflow-y-auto"></div>
                        </div>
                        <button type="button" class="p-2 rounded-full border hover:bg-gray-50" title="Current Location" onClick="handleCurrentLocation()">
                            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M11.906 1.994a8.002 8.002 0 0 1 8.09 8.421 7.996 7.996 0 0 1-1.297 3.957.996.996 0 0 1-.133.204l-.108.129c-.178.243-.37.477-.573.699l-5.112 6.224a1 1 0 0 1-1.545 0L5.982 15.26l-.002-.002a18.146 18.146 0 0 1-.309-.38l-.133-.163a.999.999 0 0 1-.13-.202 7.995 7.995 0 0 1 6.498-12.518ZM15 9.997a3 3 0 1 1-5.999 0 3 3 0 0 1 5.999 0Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="latitude" id="latitude" />
                <input type="hidden" name="longitude" id="longitude" />
                <div class="mb-4">
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Your Location</label>
                    <input type="text" id="address" name="address" readonly class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <div class="mb-4">
                    <label for="store_id" class="block mb-2 text-sm font-medium text-gray-900">Store</label>
                    <select id="store_id" name="store_id" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select store</option>
                        <?php
                            $stmt = $pdo->query("SELECT * FROM stores");
                            $stores = $stmt->fetchAll();
                            foreach ($stores as $store) {
                                echo '<option ' . ($order['store_id'] == $store['id'] ? 'selected' : '') . ' value="' . htmlspecialchars($store['id']) . '">' . htmlspecialchars($store['name']) . '</option>';
                            }                            
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="instruction" class="block mb-2 text-sm font-medium text-gray-900">Order Details</label>
                    <textarea id="instruction" name="instruction" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required><?php echo $order['instruction'] ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="delivery_fee" class="block mb-2 text-sm font-medium text-gray-900">Delivery Fee</label>
                    <input value="<?php echo $order['delivery_fee'] ?>" type="text" id="delivery_fee" name="delivery_fee" readonly class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Update</button>
                <a href="order-list.php" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Cancel</a>
            </form>
            <div id="map" class="w-full h-full min-h-[70vh]"></div>
        </div>
    </div>
</div>

<script>
    const stores = <?php echo json_encode($stores); ?>;
    const order = <?php echo json_encode($order); ?>;

    const address = document.getElementById('address');
    const latitude = document.getElementById('latitude');
    const longitude = document.getElementById('longitude');
    const delivery_fee = document.getElementById('delivery_fee');
    let map, tiles, control, customer_marker, store_marker, selectedStore, store_lat_lng, baseFee=100, perKm=2;

    map = L.map('map').setView([0,0], 13);
    tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    async function getLocationName(lat, lon){
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`);
            const data = await response.json();
            return data.display_name || 'Location not found';
        } catch (error) {
            console.error('Error fetching location name: ', error);
            return 'Location not found';
        }
    }

    function resetMap(){
        if(customer_marker){
            map.removeLayer(customer_marker);
            customer_marker = null;
        }

        if(store_marker){
            map.removeLayer(store_marker);
            store_marker = null;
        }

        if(control){
            map.removeControl(control);
            control = null;
        }
    }

    function calculateDeliveryfee(distance){
        let deliveryFee = baseFee + (perKm * distance);
        return deliveryFee.toFixed(2);
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    function handleControls(){
        if(control){
            map.removeControl(control);
            control = null;
        }

        if(store_marker && customer_marker){
            const store_lat_lng = store_marker.getLatLng();
            const customer_lat_lng = customer_marker.getLatLng();

            control = L.Routing.control({
                waypoints: [
                    L.latLng(store_lat_lng.lat, store_lat_lng.lng),
                    L.latLng(customer_lat_lng.lat, customer_lat_lng.lng)
                ],
                routeWhileDragging: true,
                router: new L.Routing.GraphHopper('9348a4ee-0d54-4f8a-9f53-6484ac3387e8'),
            }).addTo(map);

            control.on('routesfound', function(e){
                const distance = e.routes[0].summary.totalDistance / 100; // convert to km
                delivery_fee.value = calculateDeliveryfee(distance);
            })
        }
    }

    function createCustomerMarker(location, location_name){
        if(customer_marker){
            customer_marker = customer_marker.setLatLng(location);
        }else{
            customer_marker = L.marker(location).addTo(map);
        }
        customer_marker.bindPopup(`Your Location: ${location_name}`).openPopup();
    }
    
    function handleCurrentLocation(){
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(async function(position){
                const customer_lat_lng = [position.coords.latitude, position.coords.longitude];
                // const customer_lat_lng = [11.2638555325613, 123.7238943880396];

                const customer_address = await getLocationName(customer_lat_lng[0], customer_lat_lng[1]);
                address.value = customer_address;
                latitude.value = customer_lat_lng[0];
                longitude.value = customer_lat_lng[1];

                createCustomerMarker(customer_lat_lng, customer_address);
                handleControls();
            }, function (){
                alert("Unable to retrieve your location.")
            })
        }else{
            alert("Geolocation is not supported by this browser.")
        }  
    }

    const fetchSuggestions = debounce(function(query) {
        const suggestionsDiv = document.getElementById('suggestions');
        suggestionsDiv.innerHTML = ''; // Clear previous suggestions

        if (query.length > 2) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(hit => {
                        const suggestion = document.createElement('div');
                        suggestion.className = 'cursor-pointer p-1 hover:bg-gray-50 text-xs';
                        suggestion.textContent = hit.display_name;
                        suggestion.dataset.lat = hit.lat;
                        suggestion.dataset.lng = hit.lon;

                        suggestion.addEventListener('click', function() {
                            const lat = this.dataset.lat;
                            const lng = this.dataset.lng;
                            suggestionsDiv.innerHTML = ''; // Clear suggestions
                            document.getElementById('search').value = ''; // Clear input

                            address.value = hit.display_name;
                            latitude.value = lat;
                            longitude.value = lng;
                            
                            createCustomerMarker([lat, lng], hit.display_name);
                            handleControls()
                        });

                        suggestionsDiv.appendChild(suggestion);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    }, 300);

    const searchInput = document.getElementById('search');
    searchInput.addEventListener('input', function() {
        fetchSuggestions(this.value);
    });

    const storeSelect = document.getElementById('store_id');
    storeSelect.addEventListener('change', async function(){
        const selectedStore = stores.find(store => store.id == storeSelect.value);
        if (selectedStore) {
            const store_lat_lng = [selectedStore.latitude, selectedStore.longitude];

            if(store_marker){
                store_marker = store_marker.setLatLng(store_lat_lng);
            }else{
                store_marker = L.marker(store_lat_lng).addTo(map);
            }
            store_marker.bindPopup(`Store: ${selectedStore.location}`).openPopup();

            handleControls();
        }
    })

    selectedStore = stores.find(store => store.id == order.store_id);
    if (selectedStore) {
        address.value = order.address;

        customer_lat_lng = [order.latitude, order.longitude];
        store_lat_lng = [selectedStore.latitude, selectedStore.longitude];

        if(customer_marker){
            customer_marker = customer_marker.setLatLng(customer_lat_lng).bindPopup(`Your Location: ${order.address}`).openPopup();
        }else{
            customer_marker = L.marker(customer_lat_lng).addTo(map).bindPopup(`Your Location: ${order.address}`).openPopup();
        }

        if(store_marker){
            store_marker = store_marker.setLatLng(store_lat_lng);
        }else{
            store_marker = L.marker(store_lat_lng).addTo(map);
        }
        store_marker.bindPopup(`Store: ${selectedStore.location}`).openPopup();

        handleControls();
    }
</script>

<?php include '../footer.php'; ?>
