<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $totalTransactionStmt = $pdo->prepare("SELECT SUM(`total_buy`) FROM `suppliar`");
    $totalTransactionStmt->execute();
    $totalTransaction = $totalTransactionStmt->fetch(PDO::FETCH_NUM)[0];

    $totalPaidStmt = $pdo->prepare("SELECT SUM(`total_paid`) FROM `suppliar`");
    $totalPaidStmt->execute();
    $totalPaid = $totalPaidStmt->fetch(PDO::FETCH_NUM)[0];

    $totalDueStmt = $pdo->prepare("SELECT SUM(`total_due`) FROM `suppliar`");
    $totalDueStmt->execute();
    $totalDue = $totalDueStmt->fetch(PDO::FETCH_NUM)[0];

    echo json_encode([
        "total_transaction" => $totalTransaction,
        "total_paid" => $totalPaid,
        "total_due" => $totalDue
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
