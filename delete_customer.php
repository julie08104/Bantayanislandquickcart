<?php
include 'database_connection.php'; // Ensure this includes your PDO setup

$response = ['success' => false, 'message' => ''];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Customer deleted successfully.';
        } else {
            $response['message'] = 'Customer not found.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request.';
}

// Output as plain text
header('Content-Type: text/plain');
echo $response['success'] ? 'success' : 'error';
?>
