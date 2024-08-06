<?php
require_once 'app/init.php'; // Include your database connection file

// Function to add a column if it does not exist
function addColumnIfNotExists($pdo, $table, $column, $columnDefinition) {
    try {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            // Column does not exist, so add it
            $stmt = $pdo->prepare("ALTER TABLE `$table` ADD COLUMN `$column` $columnDefinition");
            $stmt->execute();
        }
    } catch (PDOException $e) {
        error_log('Error adding column: ' . $e->getMessage());
    }
}

// Call the function for 'alert_quantity' column in 'riders' table
addColumnIfNotExists($pdo, 'riders', 'alert_quantity', 'INT(11) NOT NULL');

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status, date_joined) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined]);
    } catch (PDOException $e) {
        error_log('Error creating rider: ' . $e->getMessage());
        return false;
    }
}

// Read Riders
function readRiders() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM riders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error reading riders: ' . $e->getMessage());
        return [];
    }
}

// Update Rider
function updateRider($rider_id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined) {
    global $pdo;
    try {
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
                date_joined = ? 
            WHERE rider_id = ?
        ");
        return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $date_joined, $rider_id]);
    } catch (PDOException $e) {
        error_log('Error updating rider: ' . $e->getMessage());
        return false;
    }
}

// Delete Rider
function deleteRider($rider_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM riders WHERE rider_id = ?");
        return $stmt->execute([$rider_id]);
    } catch (PDOException $e) {
        error_log('Error deleting rider: ' . $e->getMessage());
        return false;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $success = false;
    switch ($action) {
        case 'create':
            $success = createRider(
                $_POST['name'] ?? '',
                $_POST['lastname'] ?? '',
                $_POST['gender'] ?? '',
                $_POST['address'] ?? '',
                $_POST['contact_number'] ?? '',
                $_POST['email'] ?? '',
                $_POST['vehicle_type'] ?? '',
                $_POST['license_number'] ?? '',
                $_POST['status'] ?? '',
                $_POST['date_joined'] ?? ''
            );
            break;
        case 'update':
            $success = updateRider(
                $_POST['rider_id'] ?? 0,
                $_POST['name'] ?? '',
                $_POST['lastname'] ?? '',
                $_POST['gender'] ?? '',
                $_POST['address'] ?? '',
                $_POST['contact_number'] ?? '',
                $_POST['email'] ?? '',
                $_POST['vehicle_type'] ?? '',
                $_POST['license_number'] ?? '',
                $_POST['status'] ?? '',
                $_POST['date_joined'] ?? ''
            );
            break;
        case 'delete':
            $success = deleteRider($_POST['rider_id'] ?? 0);
            break;
        default:
            error_log('Invalid action: ' . $action);
            $success = false;
            break;
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit;
}

// Fetch riders for display
$riders = readRiders();
?>
