<?php
// Database connection
require '../init.php';// Ensure you include your database connection file
<?php
require 'rider_functions.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $vehicle_type = $_POST['vehicle_type'];
    $license_number = $_POST['license_number'];
    $status = $_POST['status'];
    $total_rides = $_POST['total_rides'];
    $rating = $_POST['rating'];
    $payment_method = $_POST['payment_method'];

    // Add your PDO connection here
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
        $stmt->bindParam(':total_rides', $total_rides);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':payment_method', $payment_method);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add rider.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

?>
