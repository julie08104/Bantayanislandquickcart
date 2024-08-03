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
            echo json_encode(['success' => true, 'message' => 'Rider added successfully!']);
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
<style>
        /* Additional CSS here */
        .rider-list-heading {
            text-align: center; /* Center the heading text */
            margin-bottom: 20px; /* Space between heading and table */
            font-size: 1.5rem; /* Adjust font size as needed */
            color: #333; /* Text color */
        }

        .customer-table-container {
            padding: 15px;
            border: 1px solid #dee2e6; /* Border color matching Bootstrap */
            border-radius: 5px; /* Rounded corners */
            background-color: #f8f9fa; /* Light background color */
        }
    </style>
</head>
<body>
   <!-- Print Container -->
    <div class="print-container">
        <!-- Print Image -->
        <div id="printImage" class="print-only">
            <img src="dist/img/images1.png" alt="logo" class="brand-image" style="display: block; width: 100px; height: auto;">
        </div>
        <!-- Rider List Heading -->
        <h1>Rider List</h1>
    </div>

    <button id="printButton" class="btn btn-success" style="float: right;"  onclick="printTable()">
            <i class="fas fa-print"></i> Rider List
        </button>

        <div class=" text-right mb-4" style="float: right;">
         
       
        <input class="form-control no-print" id="searchInput" type="text" placeholder="Search.." style="float: right!important;"> 
           </div>

           <div class="class="float-left mb-3"" role="group" style="float:left;">
            <button class="btn btn-success" data-toggle="modal" data-target="#addRiderModal">
            <i class="fas fa-plus"></i> Add Rider</button> <br> <br>
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
              <!--  <th>Total Rides</th>
                   <th>Rating</th>
                <th>Payment Method</th> -->
                <th  class="no-print">Actions</th>
            </tr>
        </thead>
        <tbody id="riderTableBody">
            <?php
            $counter = 1;// Initialize counter variable
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
                   <!--  <td><?= htmlentities($rider['total_rides']) ?></td>
                    <td><?= htmlentities($rider['rating']) ?></td>
                    <td><?= htmlentities($rider['payment_method']) ?></td> -->
                    <td>
                         <!-- <button class="btn btn-success" data-toggle="modal" data-target="#addRiderModal">
                                <i class="fas fa-plus"></i> Add
                            </button> -->
                            <button class="btn btn-info btn-sm" onclick="openViewModal(<?= htmlentities(json_encode($rider)) ?>)">
                                <i class="fas fa-eye">View</i></button>
                                <button class="btn btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($customer)) ?>)">
                                <i class="fas fa-edit"></i> Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteRider(<?= $rider['rider_id'] ?>)">
                                <i class="fas fa-trash">Delete</i></button>
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
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label for="total_rides">Total Rides:</label>
                        <input type="number" class="form-control" id="total_rides" name="total_rides" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <input type="number" step="0.1" class="form-control" id="rating" name="rating" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <input type="text" class="form-control" id="payment_method" name="payment_method" required>
                    </div> -->
                    <button type="submit" class="btn btn-primary">Add Rider</button>
                </form>
            </div>
        </div>
    </div>
</div>
 

         <!-- View Rider Modal -->
<div class="modal fade" id="viewRiderModal" tabindex="-1" role="dialog" aria-labelledby="viewRiderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRiderModalLabel">View Rider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="viewName">Name</label>
                        <input type="text" class="form-control" id="viewName" name="name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewLastname">Lastname</label>
                        <input type="text" class="form-control" id="viewLastname" name="lastname" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewGender">Gender</label>
                        <input type="text" class="form-control" id="viewGender" name="gender" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewAddress">Address</label>
                        <input type="text" class="form-control" id="viewAddress" name="address" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewContactNumber">Contact Number</label>
                        <input type="text" class="form-control" id="viewContactNumber" name="contact_number" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewEmail">Email</label>
                        <input type="email" class="form-control" id="viewEmail" name="email" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewVehicleType">Vehicle Type</label>
                        <input type="text" class="form-control" id="viewVehicleType" name="vehicle_type" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewLicenseNumber">License Number</label>
                        <input type="text" class="form-control" id="viewLicenseNumber" name="license_number" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewStatus">Status</label>
                        <input type="text" class="form-control" id="viewStatus" name="status" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewTotalRides">Total Rides</label>
                        <input type="number" class="form-control" id="viewTotalRides" name="total_rides" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewRating">Rating</label>
                        <input type="number" class="form-control" id="viewRating" name="rating" step="0.1" readonly>
                    </div>
                    <div class="form-group">
                        <label for="viewPaymentMethod">Payment Method</label>
                        <input type="text" class="form-control" id="viewPaymentMethod" name="payment_method" readonly>
                    </div>
                </form>
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
                    <input type="hidden" id="edit_rider_id" name="rider_id">
                    <div class="form-group">
                        <label for="edit_rider_name">Name:</label>
                        <input type="text" class="form-control" id="edit_rider_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_lastname">Last Name:</label>
                        <input type="text" class="form-control" id="edit_rider_lastname" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_gender">Gender:</label>
                        <select class="form-control" id="edit_rider_gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_address">Address:</label>
                        <input type="text" class="form-control" id="edit_rider_address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_contact_number">Contact Number:</label>
                        <input type="text" class="form-control" id="edit_rider_contact_number" name="contact_number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_email">Email:</label>
                        <input type="email" class="form-control" id="edit_rider_email" name="email" required>
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
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_total_rides">Total Rides:</label>
                        <input type="number" class="form-control" id="edit_total_rides" name="total_rides" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rating">Rating:</label>
                        <input type="number" step="0.1" class="form-control" id="edit_rating" name="rating" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_payment_method">Payment Method:</label>
                        <input type="text" class="form-control" id="edit_payment_method" name="payment_method" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Rider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle form submissions and modal opening -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

    $(document).ready(function() {
        // Add rider form submission
        $('#addRiderForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'rider_functions.php',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Rider added successfully!');
                    location.reload();
                }
            });
        });

        // Edit rider form submission
        $('#editRiderForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'rider_functions.php',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Rider updated successfully!');
                    location.reload();
                }
            });
        });
    });

    // Open edit modal and populate fields
    function openEditModal(rider) {
        $('#edit_rider_id').val(rider.rider_id);
        $('#edit_rider_name').val(rider.name);
        $('#edit_rider_lastname').val(rider.lastname);
        $('#edit_rider_gender').val(rider.gender);
        $('#edit_rider_address').val(rider.address);
        $('#edit_rider_contact_number').val(rider.contact_number);
        $('#edit_rider_email').val(rider.email);
        $('#edit_vehicle_type').val(rider.vehicle_type);
        $('#edit_license_number').val(rider.license_number);
        $('#edit_status').val(rider.status);
        $('#edit_total_rides').val(rider.total_rides);
        $('#edit_rating').val(rider.rating);
        $('#edit_payment_method').val(rider.payment_method);
        $('#editRiderModal').modal('show');
    }

    // Delete rider
    function deleteRider(rider_id) {
        if (confirm('Are you sure you want to delete this rider?')) {
            $.ajax({
                type: 'POST',
                url: 'rider_functions.php',
                data: { action: 'delete', rider_id: rider_id },
                success: function(response) {
                    alert('Rider deleted successfully!');
                    location.reload();
                }
            });
        }
    }



      function openViewModal(rider) {
        document.getElementById('viewName').value = rider.name;
        document.getElementById('viewLastname').value = rider.lastname;
        document.getElementById('viewGender').value = rider.gender;
        document.getElementById('viewAddress').value = rider.address;
        document.getElementById('viewContactNumber').value = rider.contact_number;
        document.getElementById('viewEmail').value = rider.email;
        document.getElementById('viewVehicleType').value = rider.vehicle_type;
        document.getElementById('viewLicenseNumber').value = rider.license_number;
        document.getElementById('viewStatus').value = rider.status;
        document.getElementById('viewTotalRides').value = rider.total_rides;
        document.getElementById('viewRating').value = rider.rating;
        document.getElementById('viewPaymentMethod').value = rider.payment_method;
        
        $('#viewRiderModal').modal('show');
    }
function printTable() {
    // Hide the entire Actions column (which is the last column in the table)
    var actionsColumn = document.querySelectorAll('#riderTable th:last-child, #riderTable td:last-child');
    actionsColumn.forEach(function(cell) {
        cell.style.display = 'none';
    });

    // Hide the Print button
    document.getElementById('printButton').style.display = 'none';

    // Hide DataTables pagination and length selector (if applicable)
    var dataTablePagination = document.querySelector('.dataTables_paginate');
    if (dataTablePagination) {
        dataTablePagination.style.display = 'none';
    }
    var dataTableLengthSelector = document.querySelector('.dataTables_length');
    if (dataTableLengthSelector) {
        dataTableLengthSelector.style.display = 'none';
    }

     // Create a new window for printing
    var newWin = window.open("");
    newWin.document.write('<html><head><title>Print Logo</title>');
    newWin.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
    newWin.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">');
    newWin.document.write('</head><body style="text-align: center;">');

    // Add the logo image to the new window
    newWin.document.write('<img src="dist/img/images1.png" alt="logo" style="width: 100px; height: auto;">');

    newWin.document.write('</body></html>');
    newWin.document.close();
    newWin.print();
}

    // Restore visibility of the Actions column, Print button, pagination, and length selector after printing
    actionsColumn.forEach(function(cell) {
        cell.style.display = ''; // Restore to default display type
    });
    document.getElementById('printButton').style.display = 'inline-block';
    if (dataTablePagination) {
        dataTablePagination.style.display = 'block';
    }
    if (dataTableLengthSelector) {
        dataTableLengthSelector.style.display = 'block';
    }
}

document.getElementById('searchInput').addEventListener('keyup', function() {
            var value = this.value.toLowerCase();
            document.querySelectorAll('table tbody tr').forEach(function(row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            });
        });
</script>
