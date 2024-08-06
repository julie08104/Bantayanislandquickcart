<?php
// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample'; 
$username = 'u510162695_ample'; 
$password = '1Ample_database'; 

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];

        // Prepare and execute the update statement
        $stmt = $pdo->prepare("UPDATE customer SET name = ?, lastname = ?, address = ?, contact = ?, email = ? WHERE id = ?");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $lastname);
        $stmt->bindParam(3, $address);
        $stmt->bindParam(4, $contact);
        $stmt->bindParam(5, $email);
        $stmt->bindParam(6, $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Customer updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
