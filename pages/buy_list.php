<?php
// Include your database connection file

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status)  {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO riders (name, lastname, gender, address, contact_number, email, vehicle_type, license_number, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status]);
}

// Read Riders
function readRiders() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM riders");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update Rider
function updateRider($id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number,) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE riders SET name = ?, lastname = ?, gender = ?, address = ?, contact_number = ?, email = ?, vehicle_type = ?, license_number = ?, status  = ? WHERE rider_id = ?");
    return $stmt->execute([$name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status, $id]);
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
        /* Initially hide the print image */
        #printImage {
            display: none;
        }

        @media print {
            .print-container {
                display: flex;
                align-items: center; /* Center items vertically */
                justify-content: center; /* Center items horizontally */
                text-align: center; /* Center text horizontally */
                margin-bottom: 20px; /* Add some space below the container */
            }

            .print-only {
                display: block !important;
                width: 100px; /* Adjust the width as needed */
                height: auto;
                margin-right: 20px; /* Space between the image and the text */
            }

            .no-print {
                display: none !important;
            }

            /* Ensure table cells and headers are displayed properly */
            #customerTable td, #customerTable th {
                display: table-cell !important;
            }

            .dataTables_paginate, .dataTables_length, .dataTables_filter {
                display: none !important;
            }
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
         <br> <br> <br>
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
<div class="container-fluid">
    <div class="table-responsive">
        <table id="riderTable" class="table table-bordered table-sm">
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
                    <th class="no-print">Actions</th>
                </tr>
            </thead>
            <tbody id="riderTableBody">
                <?php
                $counter = 1; // Initialize counter variable
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
              <td>
    <button class="btn btn-info btn-sm btn-custom" onclick="openViewModal(<?= htmlentities(json_encode($rider)) ?>)">
        <i class="fas fa-eye"></i> View
    </button>
    <button class="btn btn-warning btn-sm btn-custom" onclick="openEditModal(<?= htmlentities(json_encode($rider)) ?>)">
        <i class="fas fa-edit"></i> Edit
    </button>
    <button class="btn btn-danger btn-sm btn-custom" onclick="deleteRider(<?= $rider['rider_id'] ?>)">
        <i class="fas fa-trash"></i> Delete
    </button>
</td>


      </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
                   <!-- <div class="form-group">
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
                    </div>-->
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
                    <input type="hidden" name="rider_id" id="edit_rider_id">
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
                        <label for="edit_rider_vehicle_type">Vehicle Type:</label>
                        <input type="text" class="form-control" id="edit_rider_vehicle_type" name="vehicle_type" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_license_number">License Number:</label>
                        <input type="text" class="form-control" id="edit_rider_license_number" name="license_number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_status">Status:</label>
                        <input type="text" class="form-control" id="edit_rider_status" name="status" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_total_rides">Total Rides:</label>
                        <input type="text" class="form-control" id="edit_rider_total_rides" name="total_rides" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_rating">Rating:</label>
                        <input type="text" class="form-control" id="edit_rider_rating" name="rating" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rider_payment_method">Payment Method:</label>
                        <input type="text" class="form-control" id="edit_rider_payment_method" name="payment_method" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(rider) {
        document.getElementById('edit_rider_id').value = rider.rider_id;
        document.getElementById('edit_rider_name').value = rider.name;
        document.getElementById('edit_rider_lastname').value = rider.lastname;
        document.getElementById('edit_rider_gender').value = rider.gender;
        document.getElementById('edit_rider_address').value = rider.address;
        document.getElementById('edit_rider_contact_number').value = rider.contact_number;
        document.getElementById('edit_rider_email').value = rider.email;
        document.getElementById('edit_rider_vehicle_type').value = rider.vehicle_type;
        document.getElementById('edit_rider_license_number').value = rider.license_number;
        document.getElementById('edit_rider_status').value = rider.status;
        document.getElementById('edit_rider_total_rides').value = rider.total_rides;
        document.getElementById('edit_rider_rating').value = rider.rating;
        document.getElementById('edit_rider_payment_method').value = rider.payment_method;

        $('#editRiderModal').modal('show');
    }

    function openViewModal(rider) {
        var details = `
            <p><strong>Name:</strong> ${rider.name}</p>
            <p><strong>Last Name:</strong> ${rider.lastname}</p>
            <p><strong>Gender:</strong> ${rider.gender}</p>
            <p><strong>Address:</strong> ${rider.address}</p>
            <p><strong>Contact Number:</strong> ${rider.contact_number}</p>
            <p><strong>Email:</strong> ${rider.email}</p>
            <p><strong>Vehicle Type:</strong> ${rider.vehicle_type}</p>
            <p><strong>License Number:</strong> ${rider.license_number}</p>
            <p><strong>Status:</strong> ${rider.status}</p>
            <p><strong>Total Rides:</strong> ${rider.total_rides}</p>
            <p><strong>Rating:</strong> ${rider.rating}</p>
            <p><strong>Payment Method:</strong> ${rider.payment_method}</p>
        `;
        document.getElementById('viewRiderDetails').innerHTML = details;
        $('#viewRiderModal').modal('show');
    }

    function deleteRider(riderId) {
        if (confirm('Are you sure you want to delete this rider?')) {
            $.post('', { action: 'delete', rider_id: riderId }, function(response) {
                location.reload();
            });
        }
    }

    function printTable() {
        var printContent = document.getElementById('riderTable').outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

    $(document).ready(function() {
        $('#addRiderForm').submit(function(event) {
            event.preventDefault();
            $.post('', $(this).serialize(), function(response) {
                location.reload();
            });
        });

        $('#editRiderForm').submit(function(event) {
            event.preventDefault();
            $.post('', $(this).serialize(), function(response) {
                location.reload();
            });
        });
    });
</script>
</body>
</html>

