<?php
require 'db_connection.php'; // Ensure this file includes your database connection

$id = 1; // Replace with an ID to test
deleteCustomer($id);

function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
    $result = $stmt->execute([$id]);
    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
