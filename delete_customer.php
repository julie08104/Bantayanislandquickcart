<?php
// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample'; // Update with your database name
$username = 'u510162695_ample'; // Update with your username
$password = '1Ample_database'; // Update with your password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (isset($id) && is_numeric($id)) {
        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
        $result = $stmt->execute([$id]);

        // Check if the deletion was successful
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
    }
}
?>
