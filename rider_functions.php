<?php
require_once 'app/init.php'; // Include your database connection file

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Function to add a column if it does not exist
function addColumnIfNotExists($pdo, $table, $column, $columnDefinition) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$column]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result === false) {
        // Column does not exist, so add it
        $stmt = $pdo->prepare("ALTER TABLE `$table` ADD COLUMN `$column` $columnDefinition");
        $stmt->execute();
    }
}

// Call the function for 'alert_quantity' column in 'riders' table
addColumnIfNotExists($pdo, 'riders', 'alert_quantity', 'INT(11) NOT NULL');

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined, $total_rides, $rating, $payment_method) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status, date_joined, total_rides, rating, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined, $total_rides, $rating, $payment_method]);
}

// Read Riders
function readRiders() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM riders");
    if ($stmt) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error fetching riders: ' . $pdo->errorInfo()[2]]);
        exit;
    }
}

// Update Rider
function updateRider($rider_id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined, $total_rides, $rating, $payment_method) {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE riders 
        SET 
            name = ?, 
            lastname = ?, 
            gender = ?, 
            address = ?, 
            contact_number = ?, 
            email = ?, 
            vehicle_type = ?, 
            license_number = ?, 
            status = ?, 
            date_joined = ?, 
            total_rides = ?, 
            rating = ?, 
            payment_method = ? 
        WHERE rider_id = ?
    ");
    return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined, $total_rides, $rating, $payment_method, $rider_id]);
}

// Delete Rider
function deleteRider($rider_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM riders WHERE rider_id = ?");
    return $stmt->execute([$rider_id]);
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'create':
            $success = createRider(
                $_POST['name'],
                $_POST['lastname'],
                $_POST['gender'],
                $_POST['address'],
                $_POST['contact_number'],
                $_POST['email'],
                $_POST['vehicle_type'],
                $_POST['license_number'],
                $_POST['status'],
                $_POST['date_joined'],
                $_POST['total_rides'],
                $_POST['rating'],
                $_POST['payment_method']
            );
            break;
        case 'update':
            $success = updateRider(
                $_POST['rider_id'],
                $_POST['name'],
                $_POST['lastname'],
                $_POST['gender'],
                $_POST['address'],
                $_POST['contact_number'],
                $_POST['email'],
                $_POST['vehicle_type'],
                $_POST['license_number'],
                $_POST['status'],
                $_POST['date_joined'],
                $_POST['total_rides'],
                $_POST['rating'],
                $_POST['payment_method']
            );
            break;
        case 'delete':
            $success = deleteRider($_POST['rider_id']);
            break;
        default:
            $success = false;
            break;
    }
    echo json_encode(['success' => $success]);
    exit;
}

// Fetch riders for display
$riders = readRiders();
echo json_encode(['success' => true, 'riders' => $riders]);
exit;
?>
