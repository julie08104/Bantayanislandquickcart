<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status, total_rides, rating, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method]);
    } catch (Exception $e) {
        return false;
    }
}

// Read Riders
function readRiders() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM riders");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Update Rider
function updateRider($id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE riders SET name = ?, lastname = ?, gender = ?, address = ?, contact_number = ?, email = ?, vehicle_type = ?, license_number = ?, status = ?, total_rides = ?, rating = ?, payment_method = ? WHERE rider_id = ?");
        return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $total_rides, $rating, $payment_method, $id]);
    } catch (Exception $e) {
        return false;
    }
}

// Delete Rider
function deleteRider($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM riders WHERE rider_id = ?");
        return $stmt->execute([$id]);
    } catch (Exception $e) {
        return false;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            $success = createRider($_POST['name'], $_POST['lastname'], $_POST['gender'], $_POST['address'], $_POST['contact_number'], $_POST['email'], $_POST['vehicle_type'], $_POST['license_number'], $_POST['status'], $_POST['total_rides'], $_POST['rating'], $_POST['payment_method']);
            echo json_encode(['success' => $success, 'message' => $success ? 'Rider added successfully!' : 'Failed to add rider.']);
            break;
        case 'update':
            $success = updateRider($_POST['rider_id'], $_POST['name'], $_POST['lastname'], $_POST['gender'], $_POST['address'], $_POST['contact_number'], $_POST['email'], $_POST['vehicle_type'], $_POST['license_number'], $_POST['status'], $_POST['total_rides'], $_POST['rating'], $_POST['payment_method']);
            echo json_encode(['success' => $success, 'message' => $success ? 'Rider updated successfully!' : 'Failed to update rider.']);
            break;
        case 'delete':
            $success = deleteRider($_POST['rider_id']);
            echo json_encode(['success' => $success, 'message' => $success ? 'Rider deleted successfully!' : 'Failed to delete rider.']);
            break;
    }
    exit;
}

// Fetch riders for display
$riders = readRiders();
?>

<div class="container-fluid">
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
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addRiderModal">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            <button class="btn btn-info btn-sm" onclick="openViewModal(<?= htmlentities(json_encode($rider)) ?>)">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= htmlentities(json_encode($rider)) ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteRider(<?= $rider['rider_id'] ?>)">
                                <i class="fas fa-trash"></i> Delete
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
                        <label for="rider_vehicle_type">Vehicle Type:</label>
                        <input type="text" class="form-control" id="rider_vehicle_type" name="vehicle_type" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_license_number">License Number:</label>
                        <input type="text" class="form-control" id="rider_license_number" name="license_number" required>
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
                        <input type="number" class="form-control" id="rider_rating" name="rating" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="rider_payment_method">Payment Method:</label>
                        <input type="text" class="form-control" id="rider_payment_method" name="payment_method" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Rider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Rider Modal -->
<div class="modal fade" id="viewRiderModal" tabindex="-1" role="dialog" aria-labelledby="viewRiderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRiderModalLabel">View Rider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="view_name">Name:</label>
                    <input type="text" class="form-control" id="view_name" readonly>
                </div>
                <div class="form-group">
                    <label for="view_lastname">Last Name:</label>
                    <input type="text" class="form-control" id="view_lastname" readonly>
                </div>
                <div class="form-group">
                    <label for="view_gender">Gender:</label>
                    <input type="text" class="form-control" id="view_gender" readonly>
                </div>
                <div class="form-group">
                    <label for="view_address">Address:</label>
                    <input type="text" class="form-control" id="view_address" readonly>
                </div>
                <div class="form-group">
                    <label for="view_contact_number">Contact Number:</label>
                    <input type="text" class="form-control" id="view_contact_number" readonly>
                </div>
                <div class="form-group">
                    <label for="view_email">Email:</label>
                    <input type="text" class="form-control" id="view_email" readonly>
                </div>
                <div class="form-group">
                    <label for="view_vehicle_type">Vehicle Type:</label>
                    <input type="text" class="form-control" id="view_vehicle_type" readonly>
                </div>
                <div class="form-group">
                    <label for="view_license_number">License Number:</label>
                    <input type="text" class="form-control" id="view_license_number" readonly>
                </div>
                <div class="form-group">
                    <label for="view_status">Status:</label>
                    <input type="text" class="form-control" id="view_status" readonly>
                </div>
                <div class="form-group">
                    <label for="view_total_rides">Total Rides:</label>
                    <input type="number" class="form-control" id="view_total_rides" readonly>
                </div>
                <div class="form-group">
                    <label for="view_rating">Rating:</label>
                    <input type="number" class="form-control" id="view_rating" step="0.1" readonly>
                </div>
                <div class="form-group">
                    <label for="view_payment_method">Payment Method:</label>
                    <input type="text" class="form-control" id="view_payment_method" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Rider Modal -->
<div class="modal fade" id="editRiderModal" tabindex="-1" role="dialog" aria-labelledby="editRiderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRiderModalLabel">Edit Rider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRiderForm" method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="rider_id" id="edit_rider_id">
                    <div class="form-group">
                        <label for="edit_name">Name:</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_lastname">Last Name:</label>
                        <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_gender">Gender:</label>
                        <select class="form-control" id="edit_gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_address">Address:</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_contact_number">Contact Number:</label>
                        <input type="text" class="form-control" id="edit_contact_number" name="contact_number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email:</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_vehicle_type">Vehicle Type:</label>
                        <input type="text" class="form-control" id="edit_vehicle_type" name="vehicle_type" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_license_number">License Number:</label>
                        <input type="text" class="form-control" id="edit_license_number" name="license_number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status:</label>
                        <input type="text" class="form-control" id="edit_status" name="status" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_total_rides">Total Rides:</label>
                        <input type="number" class="form-control" id="edit_total_rides" name="total_rides" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rating">Rating:</label>
                        <input type="number" class="form-control" id="edit_rating" name="rating" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_payment_method">Payment Method:</label>
                        <input type="text" class="form-control" id="edit_payment_method" name="payment_method" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('#addRiderForm').on('submit', function (e) {
            e.preventDefault();
            // Code to add rider details
        });

        $('.viewRiderButton').on('click', function () {
            const riderId = $(this).data('id');
            // Code to fetch and display rider details in view modal
        });

        $('.editRiderButton').on('click', function () {
            const riderId = $(this).data('id');
            // Code to fetch and display rider details in edit modal
        });

        $('#editRiderForm').on('submit', function (e) {
            e.preventDefault();
            // Code to update rider details
        });
    });
</script>
</body>
</html>