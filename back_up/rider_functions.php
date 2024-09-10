<?php
require_once 'app/init.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    } catch (Exception $e) {
        error_log('Error in addColumnIfNotExists: ' . $e->getMessage());
    }
}

// Call the function for 'alert_quantity' column in 'riders' table
addColumnIfNotExists($pdo, 'riders', 'alert_quantity', 'INT(11) NOT NULL');

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status]);
    } catch (Exception $e) {
        error_log('Error in createRider: ' . $e->getMessage());
        return false;
    }
}

// Read Riders
function readRiders() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM riders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error in readRiders: ' . $e->getMessage());
        return [];
    }
}

// Update Rider
function updateRider($rider_id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status) {
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
                status = ?
            WHERE rider_id = ?
        ");
        return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $rider_id]);
    } catch (Exception $e) {
        error_log('Error in updateRider: ' . $e->getMessage());
        return false;
    }
}

// Delete Rider
function deleteRider($rider_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM riders WHERE rider_id = ?");
        return $stmt->execute([$rider_id]);
    } catch (Exception $e) {
        error_log('Error in deleteRider: ' . $e->getMessage());
        return false;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => 'Unknown error'];
    
    $action = $_POST['action'] ?? '';
    
    try {
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
                    $_POST['status']
                );
                $response['success'] = $success;
                $response['message'] = $success ? 'Rider added successfully!' : 'Failed to add rider.';
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
                    $_POST['status']
                );
                $response['success'] = $success;
                $response['message'] = $success ? 'Rider updated successfully!' : 'Failed to update rider.';
                break;
            case 'delete':
                $success = deleteRider($_POST['rider_id']);
                $response['success'] = $success;
                $response['message'] = $success ? 'Rider deleted successfully!' : 'Failed to delete rider.';
                break;
            default:
                $response['message'] = 'Invalid action';
                break;
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
    
    echo json_encode($response);
    exit;
}

// Fetch riders for display
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    $riders = readRiders();
    echo json_encode(['success' => true, 'riders' => $riders]);
    exit;
}
?>
