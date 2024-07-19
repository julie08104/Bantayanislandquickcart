<?php

$response = ['success' => false, 'message' => 'An error occurred'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure you receive all the required data
    if (isset($_POST['id'], $_POST['name'], $_POST['lastname'], $_POST['company'], $_POST['address'], $_POST['contact'], $_POST['email'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $company = $_POST['company'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        
        // Prepare and execute your update statement
        $stmt = $pdo->prepare("UPDATE customers SET name = ?, lastname = ?, company = ?, address = ?, contact = ?, email = ? WHERE id = ?");
        if ($stmt->execute([$name, $lastname, $company, $address, $contact, $email, $id])) {
            $response['success'] = true;
            $response['message'] = 'Customer updated successfully';
        } else {
            $response['message'] = 'Failed to update customer';
        }
    } else {
        $response['message'] = 'Missing required fields';
    }
} else {
    $response['message'] = 'Invalid request method';
}

// Return the response as JSON
echo json_encode($response);
?>
