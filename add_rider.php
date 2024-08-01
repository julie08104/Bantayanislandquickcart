<?php
// Include database connection and functions
require '../init.php'; // Ensure this file sets up the $pdo variable correctly
require 'rider_functions.php'; // Ensure this file doesn’t conflict with the PDO setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize POST data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $vehicle_type = filter_input(INPUT_POST, 'vehicle_type', FILTER_SANITIZE_STRING);
    $license_number = filter_input(INPUT_POST, 'license_number', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $total_rides = filter_input(INPUT_POST, 'total_rides', FILTER_SANITIZE_NUMBER_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    // Add your PDO connection here and check for connection issues
    try {
        $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status, total_rides, rating, payment_method, date_joined) VALUES (:name, :lastname, :gender, :address, :contact_number, :email, :vehicle_type, :license_number, :status, :total_rides, :rating, :payment_method, NOW())");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':vehicle_type', $vehicle_type);
        $stmt->bindParam(':license_number', $license_number);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':total_rides', $total_rides, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_STR);
        $stmt->bindParam(':payment_method', $payment_method);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add rider.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
