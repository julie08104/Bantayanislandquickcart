<?php
// Database connection details
$dsn = 'mysql:host=localhost;dbname=ample'; // Update with your database name
$username = 'root'; // Update with your username
$password = ''; // Update with your password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if ID is set
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        // Prepare the delete statement
        $stmt = $pdo->prepare("DELETE FROM `riders` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Return a success response
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } else {
            // Return an error response
            echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

