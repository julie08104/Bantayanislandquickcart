<?php
// Debugging lines
echo 'Current Working Directory: ' . getcwd() . '<br>';
echo 'Resolved Path: ' . dirname(__DIR__) . '/init.php' . '<br>';

// Corrected path to init.php
require_once dirname(__DIR__) . '/init.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure 'id' is present in POST data
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];

        // Validate the ID (e.g., check if it's a numeric value)
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            try {
                // Prepare and execute the delete statement
                $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'User deleted successfully';
                } else {
                    $response['message'] = 'Failed to delete user';
                }
            } catch (PDOException $e) {
                $response['message'] = 'Database error: ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'Invalid ID format';
        }
    } else {
        $response['message'] = 'ID not provided';
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
