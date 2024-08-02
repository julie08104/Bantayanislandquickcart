<?php
// Include your database connection file

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status, total_rides, rating, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method]);
}

// Read Riders
function readRiders() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM riders");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update Rider
function updateRider($id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE riders SET name = ?, lastname = ?, gender = ?, address = ?, contact_number = ?, email = ?, vehicle_type = ?, license_number = ?, status = ?, total_rides = ?, rating = ?, payment_method = ? WHERE rider_id = ?");
    return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method, $id]);
}

// Delete Rider
function deleteRider($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM riders WHERE rider_id = ?");
    return $stmt->execute([$id]);
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'create':
            createRider($_POST['name'], $_POST['lastname'], $_POST['gender'], $_POST['address'], $_POST['contact_number'], $_POST['email'], $_POST['vehicle_type'], $_POST['license_number'], $_POST['status'], $_POST['total_rides'], $_POST['rating'], $_POST['payment_method']);
            echo json_encode(['success' => true, 'message' => 'Rider added successfully!', 'rider' => $_POST]);
            exit;
        case 'update':
            updateRider($_POST['rider_id'], $_POST['name'], $_POST['lastname'], $_POST['gender'], $_POST['address'], $_POST['contact_number'], $_POST['email'], $_POST['vehicle_type'], $_POST['license_number'], $_POST['status'], $_POST['total_rides'], $_POST['rating'], $_POST['payment_method']);
            echo json_encode(['success' => true, 'message' => 'Rider updated successfully!']);
            exit;
        case 'delete':
            deleteRider($_POST['rider_id']);
            echo json_encode(['success' => true, 'message' => 'Rider deleted successfully!']);
            exit;
    }
}

// Fetch riders for display
$riders = readRiders();
?>

<!-- HTML and Bootstrap Front-end -->
<br>
<div class="container-fluid" style="margin-left: 0px!important;">
    <h1>Rider List</h1>

    <button id="printButton" class="btn btn-success" style="float: right;" onclick="printTable()">
        <i class="fas fa-print"></i> Print
    </button>

    <div class="text-right mb-4" style="float: right;">
        <input class="form-control no-print" id="searchInput" type="text" placeholder="Search.." style="float: right!important;"> 
    </div>

    <!-- Rider Table -->
    <table id="riderTable" class="table table-bordered table-responsive-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Vehicle Type</th>
                <th>License Number</th>
                <th>Status</th>
                <th>Total Rides</th>
                <!-- <th>Rating</th>
                <th>Payment Method</th> -->
                <th class="no-print">Actions</th>
            </tr>
        </thead>
        <tbody id="riderTableBody">
            <?php
            $counter = 1;
            foreach ($riders as $rider): ?>
                <tr>
                    <td><?= $counter++ ?></td>
                    <td><?= htmlentities($rider['name']) ?></td>
                    <td><?= htmlentities($rider['lastname']) ?></td>
                    <td><?= htmlentities($rider['gender']) ?></td>
                    <td><?= htmlentities($rider['address']) ?></td>
                    <td><?= htmlentities($rider['contact_number']) ?></td>
                    <td><?= htmlentities($rider['email']) ?></td>
                    <td><?= htmlentities($rider['vehicle_type']) ?></td>
                    <td><?= htmlentities($rider['license_number']) ?></td>
                    <td><?= htmlentities($rider['status']) ?></td>
                    <td><?= htmlentities($rider['total_rides']) ?></td>
                    <!-- <td><?= htmlentities($rider['rating']) ?></td>
                    <td><?= htmlentities($rider['payment_method']) ?></td> -->
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addRiderModal">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            <button class="btn btn-info btn-sm" onclick="openViewModal(<?= htmlentities(json_encode($rider)) ?>)">
                                <i class="fas fa-eye"> View</i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= htmlentities(json_encode($rider)) ?>)">
                                <i class="fas fa-edit"> Edit</i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteRider(<?= $rider['rider_id'] ?>)">
                                <i class="fas fa-trash"> Delete</i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Rider Modal -->
<div class="modal fade" id="addRiderModal" tabindex="-1" role="dialog" aria-labelledby="addRiderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRiderModalLabel">Add New Rider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addRiderForm" method="POST">
                    <input type="hidden" name="action" value="create">
                    <div class="form-group">
                        <label for="rider_name">Name:</label>
                        <input type="text" class="form-control" id="rider_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_lastname">Last Name:</label>
                        <input type="text" class="form-control" id="rider_lastname" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_gender">Gender:</label>
                        <select class="form-control" id="rider_gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rider_address">Address:</label>
                        <input type="text" class="form-control" id="rider_address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_contact_number">Contact Number:</label>
                        <input type="text" class="form-control" id="rider_contact_number" name="contact_number" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_email">Email:</label>
                        <input type="email" class="form-control" id="rider_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type:</label>
                        <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" required>
                    </div>
                    <div class="form-group">
                        <label for="license_number">License Number:</label>
                        <input type="text" class="form-control" id="license_number" name="license_number" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_status">Status:</label>
                        <input type="text" class="form-control" id="rider_status" name="status" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_total_rides">Total Rides:</label>
                        <input type="number" class="form-control" id="rider_total_rides" name="total_rides" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_rating">Rating:</label>
                        <input type="number" step="0.1" class="form-control" id="rider_rating" name="rating" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <input type="text" class="form-control" id="payment_method" name="payment_method" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Rider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- AJAX script to handle form submission and table update -->
<script>
document.getElementById('addRiderForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let formData = new FormData(this);

    fetch('your_php_file.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let riderTableBody = document.getElementById('riderTableBody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>New</td>
                <td>${data.rider.name}</td>
                <td>${data.rider.lastname}</td>
                <td>${data.rider.gender}</td>
                <td>${data.rider.address}</td>
                <td>${data.rider.contact_number}</td>
                <td>${data.rider.email}</td>
                <td>${data.rider.vehicle_type}</td>
                <td>${data.rider.license_number}</td>
                <td>${data.rider.status}</td>
                <td>${data.rider.total_rides}</td>
                <td>
                    <div class="btn-group-vertical" role="group">
                        <button class="btn btn-info btn-sm">
                            <i class="fas fa-eye"> View</i>
                        </button>
                        <button class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"> Edit</i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"> Delete</i>
                        </button>
                    </div>
                </td>
            `;
            riderTableBody.appendChild(newRow);
            $('#addRiderModal').modal('hide');
        }
    });
});
</script>

