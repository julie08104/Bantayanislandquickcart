<?php
require '../../config.php';

// Get the user ID from POST data
if (isset($_POST['id'])) {
    $userId = (int) $_POST['id'];

    // Prepare SQL statement to delete the user
    $stmt = $pdo->prepare('DELETE FROM customers WHERE id = :id');
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        // Return a success response
        echo json_encode(['success' => true]);
    } else {
        // Return an error response
        echo json_encode(['success' => false]);
    }
} else {
    // Handle the case where no ID was provided
    echo json_encode(['success' => false]);
}
?>
