<?php
require_once '../init.php'; 
$picture = ''; // Set default value or handle file upload here

// Process form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password']; // Remember to hash or handle securely
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$middleName = $_POST['middle_name'];
$address = $_POST['address'];
$verificationCode = $_POST['verification_code'];

// Perform database insert
// Replace with your database connection and insert query
// Example code to execute query and return JSON response
$response = array('success' => true, 'message' => 'User added successfully');
echo json_encode($response);
?>
