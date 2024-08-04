<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Ensure your DB connection is included

$response = array('success' => false, 'message' => 'Something went wrong.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log incoming POST data for debugging
    file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);

    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $company = isset($_POST['company']) ? $_POST['company'] : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO customer (name, lastname, address, contact, email, company) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $lastname, $address, $contact, $email, $company])) {
            $response['success'] = true;
            $response['message'] = 'Customer added successfully.';
        } else {
            $response['message'] = 'Failed to add customer.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>
