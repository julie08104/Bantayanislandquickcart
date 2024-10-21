<?php
    require '../config.php';
    require '../auth_check.php';

    $id = isset($_GET['id']) ? intval($_GET['id']) : '';
    $stmt = $pdo->prepare("SELECT * FROM stores WHERE id = ?");
    $stmt->execute([$id]);
    $store = $stmt->fetch();
    
    if (!$store) {
        header("Location: store-list.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        
        $stmt = $pdo->prepare("UPDATE stores SET name = ?, location = ?, latitude = ?, longitude = ? WHERE id = ?");
        if ($stmt->execute([$name, $location, $latitude, $longitude, $id])) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Store updated successfully.'];
            header("Location: store-list.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Store update failed. Please try again.'];
            header("Location: store-edit.php?id=" . $id);
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
            <h1 class="text-2xl">Edit Store</h1>
        </div>
        <div class="max-w-md">
            <form method="POST">
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $store['name'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
                <div class="mb-4">
                    <label for="location" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input type="text" id="location" name="location" value="<?php echo $store['location'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
                <div class="mb-4">
                    <label for="latitude" class="block mb-2 text-sm font-medium text-gray-900">Latitude</label>
                    <input type="text" id="latitude" name="latitude" value="<?php echo $store['latitude'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
                <div class="mb-4">
                    <label for="longitude" class="block mb-2 text-sm font-medium text-gray-900">Longitude</label>
                    <input type="text" id="longitude" name="longitude" value="<?php echo $store['longitude'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                </div>
                <button type="submit" class="mb-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Update</button>
                <a href="store-list.php" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>