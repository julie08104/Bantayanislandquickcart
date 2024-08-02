<?php
// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
$username = 'u510162695_ample';
$password = '1Ample_database';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
        // Collect data from POST request
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

        // Prepare and execute the insert statement
        $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status, total_rides, rating, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Rider added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding rider.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
