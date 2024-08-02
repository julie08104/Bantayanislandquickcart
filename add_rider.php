<?php
// Include database connection
require_once '../init.php'; // Adjust path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get POST data
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $vehicle_type = $_POST['vehicle_type'];
        $license_number = $_POST['license_number'];
        $status = $_POST['status'];
        $total_rides = $_POST['total_rides'];
        $rating = $_POST['rating'];
        $payment_method = $_POST['payment_method'];

        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact, email, vehicle_type, license_number, status, total_rides, rating, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Execute the statement
        $stmt->execute([$name, $lastname, $gender, $address, $contact, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add rider.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
