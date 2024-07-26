<?php
$dsn = 'mysql:host=localhost;dbname=ample';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = 'SELECT * FROM riders';
    $stmt = $pdo->query($query);
    $riders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($riders);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riders Dashboard</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head
>
<style type="text/css">
    body {
    font-family: Arial, sans-serif;
}

.sidebar {
    background-color: #f8f9fa;
    padding: 15px;
    height: 100vh;
}

.sidebar .nav-link {
    color: #333;
}

.sidebar .nav-link.active {
    font-weight: bold;
    color: #007bff;
}

.container-fluid {
    padding: 0;
}

.row {
    margin: 0;
}

.col-md-3 {
    padding: 0;
}

.col-md-9 {
    padding: 20px;
}


</style>
<body>
    <div class="container-fluid">
       <div class="row">
            <div class="col-md-3">
                <?php include 'sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <h1>Riders Dashboard</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Lastname</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Vehicle Type</th>
                            <th>License Number</th>
                            <th>Status</th>
                            <th>Date Joined</th>
                            <th>Total Rides</th>
                            <th>Rating</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody id="ridersTableBody">
                        <!-- Data will be injected here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js">document.addEventListener('DOMContentLoaded', function () {
   
});</script>
</body>
</html>