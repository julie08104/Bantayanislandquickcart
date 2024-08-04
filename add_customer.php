<?php
// Include your database connection file
include 'database_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $company = !empty($_POST['company']) ? filter_var($_POST['company'], FILTER_SANITIZE_STRING) : null;

    // Validate input
    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO customers (name, lastname, address, contact, email, company) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$name, $lastname, $address, $contact, $email, $company]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Customer added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add customer.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
