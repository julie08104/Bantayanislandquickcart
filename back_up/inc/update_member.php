<?php
// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ample", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $company = $_POST['company'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // Validate required fields
    if (empty($id) || empty($name) || empty($lastname) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
        exit();
    }

    try {
        // Update customer record
        $stmt = $pdo->prepare("UPDATE customers SET name = ?, lastname = ?, company = ?, address = ?, contact = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $lastname, $company, $address, $contact, $email, $id]);

        if ($stmt->rowCount()) {
            echo json_encode(['success' => true, 'message' => 'Customer updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes were made.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
