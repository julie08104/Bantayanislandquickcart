<?php
require_once 'app/init.php'; // Include your database connection file

// Function to add a column if it does not exist
function addColumnIfNotExists($pdo, $table, $column, $columnDefinition) {
    try {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM $table LIKE ?");
        $stmt->execute([$column]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            // Column does not exist, so add it
            $stmt = $pdo->prepare("ALTER TABLE $table ADD COLUMN $column $columnDefinition");
            $stmt->execute();
        }
    } catch (PDOException $e) {
        error_log('Error adding column: ' . $e->getMessage());
    }
}

// Call the function for 'alert_quantity' column in 'riders' table
addColumnIfNotExists($pdo, 'riders', 'alert_quantity', 'INT(11) NOT NULL');

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status]);

        if (!$result) {
            error_log('Create query failed: ' . implode(', ', $stmt->errorInfo()));
        } else {
            error_log('Create query succeeded');
        }

        return $result;
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
function updateRider($rider_id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status) {
    global $pdo;
    try {
        // Log the data being updated for debugging
        error_log("Updating rider with ID: $rider_id");
        error_log("Data: name='$name', lastname='$lastname', gender='$gender', address='$address', contact_number='$contact_number', email='$email', vehicle_type='$vehicle_type', license_number='$license_number', status='$status'");

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

        $result = $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $rider_id]);

        if (!$result) {
            error_log('Update query failed: ' . implode(', ', $stmt->errorInfo()));
        } else {
            error_log('Update query succeeded');
        }

        return $result;
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
        $result = $stmt->execute([$rider_id]);

        if (!$result) {
            error_log('Delete query failed: ' . implode(', ', $stmt->errorInfo()));
        } else {
            error_log('Delete query succeeded');
        }

        return $result;
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
                $_POST['status'] ?? ''
            );
            break;
        case 'update':
            $success = updateRider(
                $_POST['rider_id'] ?? '',
                $_POST['name'] ?? '',
                $_POST['lastname'] ?? '',
                $_POST['gender'] ?? '',
                $_POST['address'] ?? '',
                $_POST['contact_number'] ?? '',
                $_POST['email'] ?? '',
                $_POST['vehicle_type'] ?? '',
                $_POST['license_number'] ?? '',
                $_POST['status'] ?? ''
            );
            break;
        case 'delete':
            $success = deleteRider($_POST['rider_id'] ?? '');
            break;
        default:
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
