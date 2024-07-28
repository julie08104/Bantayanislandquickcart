<?php
// Start session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debugging function to check file existence
function checkFileExists($file) {
    if (!file_exists($file)) {
        die("File not found: " . $file);
    }
}

// Define file paths
$configPath = __DIR__ . '/config/config.php';
$objectPath = __DIR__ . '/classes/Object.php';
$userPath = __DIR__ . '/classes/User.php';
$functionsPath = __DIR__ . '/functions.php';

// Check if files exist before including
checkFileExists($configPath);
checkFileExists($objectPath);
checkFileExists($userPath);
checkFileExists($functionsPath);

// Include necessary files
require_once $configPath;
require_once $objectPath;
require_once $userPath;
require_once $functionsPath;

// Initialize PDO connection
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=u510162695_ample", 'u510162695_ample', '1Ample_database', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize Objects and User classes with PDO
$obj = new Objects($pdo);
$Ouser = new User($pdo);

// Other initialization code as needed

?>
