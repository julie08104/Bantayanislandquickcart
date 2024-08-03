<?php
header('Content-Type: application/json'); // Ensure JSON content type

$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
$username = 'u510162695_ample';
$password = '1Ample_database';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Log all POST data for debugging
    error_log('POST data: ' . print_r($_POST, true));

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        error_log("Received ID: " . $id);

        if (filter_var($id, FILTER_VALIDATE_INT) !== false) {
            $id = intval($id);
            error_log("Validated ID: " . $id);

            $stmt = $pdo->prepare("DELETE FROM `customer` WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0) {
                    echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No customer found with the provided ID.']);
                }
            } else {
                error_log("Execute failed: " . implode(" ", $stmt->errorInfo()));
                echo json_encode(['success' => false, 'message' => 'Error executing delete operation.']);
            }
        } else {
            error_log('ID is not a valid integer: ' . $id);
            echo json_encode(['success' => false, 'message' => 'Invalid or missing customer ID.']);
        }
    } else {
        error_log('ID not set in POST data.');
        echo json_encode(['success' => false, 'message' => 'Invalid or missing customer ID.']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: An unexpected error occurred.']);
}
?>

