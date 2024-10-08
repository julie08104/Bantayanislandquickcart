<?php
// Include your database connection file

// Create Rider
function createRider($name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status) {
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
function updateRider($id, $name, $lastname, $gender, $address, $contact_number, $email, $vehicle_type, $license_number, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE riders SET name = ?, lastname = ?, gender = ?, address = ?, contact_number = ?, email = ?, vehicle_type = ?, license_number = ?, status = ? WHERE rider_id = ?");
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
            createRider($_POST['name'], $_POST['lastname'], $_POST['gender'], $_POST['address'], $_POST['contact_number'], $_POST['email'], $_POST['vehicle_type'], $_POST['license_number'], $_POST['status']);
            echo json_encode(['success' => true, 'message' => 'Rider added successfully!']);
            exit;
        case 'update':
            updateRider($_POST['rider_id'], $_POST['name'], $_POST['lastname'], $_POST['gender'], $_POST['address'], $_POST['contact_number'], $_POST['email'], $_POST['vehicle_type'], $_POST['license_number'], $_POST['status']);
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

           <div class="float-left mb-3" role="group" style="float:left;">
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
                <button class="btn btn-info btn-sm" onclick='openViewModal(${JSON.stringify(rider)})'>
    <i class="fas fa-eye"></i> View
</button>            
                <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= htmlentities(json_encode($rider)) ?>)">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger btn-sm" onclick="deleteRider(<?= $rider['rider_id'] ?>)">
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRiderModalLabel">View Rider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="view_rider_name"></span></p>
                <p><strong>Last Name:</strong> <span id="view_rider_lastname"></span></p>
                <p><strong>Gender:</strong> <span id="view_rider_gender"></span></p>
                <p><strong>Address:</strong> <span id="view_rider_address"></span></p>
                <p><strong>Contact Number:</strong> <span id="view_rider_contact_number"></span></p>
                <p><strong>Email:</strong> <span id="view_rider_email"></span></p>
                <p><strong>Vehicle Type:</strong> <span id="view_rider_vehicle_type"></span></p>
                <p><strong>License Number:</strong> <span id="view_rider_license_number"></span></p>
                <p><strong>Status:</strong> <span id="view_rider_status"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                   <!-- <div class="form-group">
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
                    </div>-->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- JavaScript to handle form submissions and modal opening -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script>
        $(document).ready(function() {
            // Add rider form submission
            $('#addRiderForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'rider_functions.php',
                    data: $(this).serialize() + '&action=create',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'Rider added successfully!',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Error adding rider.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while adding the rider.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Edit rider form submission
            $('#editRiderForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'rider_functions.php',
                    data: $(this).serialize() + '&action=update',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Rider updated successfully!',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error updating rider.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Fetch riders function
            function fetchRiders() {
                $.ajax({
                    type: 'GET',
                    url: 'rider_functions.php',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#riderTableBody').empty();
                            data.riders.forEach(function(rider) {
                                $('#riderTableBody').append(
                                    `<tr>
                                        <td>${rider.rider_id}</td>
                                        <td>${rider.name}</td>
                                        <td>${rider.lastname}</td>
                                        <td>${rider.gender}</td>
                                        <td>${rider.address}</td>
                                        <td>${rider.contact_number}</td>
                                        <td>${rider.email}</td>
                                        <td>${rider.vehicle_type}</td>
                                        <td>${rider.license_number}</td>
                                        <td>${rider.status}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" onclick='openViewModal(${JSON.stringify(rider)})'>
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-warning btn-sm" onclick='openEditModal(${JSON.stringify(rider)})'>
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick='deleteRider(${rider.rider_id})'>
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>`
                                );
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error fetching riders.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while fetching riders.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            // Call fetchRiders on page load
            fetchRiders();

            // Delete rider function
            window.deleteRider = function(rider_id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'rider_functions.php',
                            data: { action: 'delete', rider_id: rider_id },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'Rider has been deleted.',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error deleting rider.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            };

            // Open Edit Modal
            window.openEditModal = function(rider) {
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

                $('#editRiderModal').modal('show');
            };

            // Open View Modal
window.openViewModal = function(rider) {
    document.getElementById('view_rider_name').textContent = rider.name;
    document.getElementById('view_rider_lastname').textContent = rider.lastname;
    document.getElementById('view_rider_gender').textContent = rider.gender;
    document.getElementById('view_rider_address').textContent = rider.address;
    document.getElementById('view_rider_contact_number').textContent = rider.contact_number;
    document.getElementById('view_rider_email').textContent = rider.email;
    document.getElementById('view_rider_vehicle_type').textContent = rider.vehicle_type;
    document.getElementById('view_rider_license_number').textContent = rider.license_number;
    document.getElementById('view_rider_status').textContent = rider.status;

    $('#viewRiderModal').modal('show');
};


              
            // Print Table Function
            window.printTable = function() {
                var actionsColumn = document.querySelectorAll('#riderTable th:last-child, #riderTable td:last-child');
                actionsColumn.forEach(function(cell) {
                    cell.style.display = 'none';
                });
                document.getElementById('printButton').style.display = 'none';
                var dataTablePagination = document.querySelector('.dataTables_paginate');
                if (dataTablePagination) {
                    dataTablePagination.style.display = 'none';
                }
                var dataTableLengthSelector = document.querySelector('.dataTables_length');
                if (dataTableLengthSelector) {
                    dataTableLengthSelector.style.display = 'none';
                }
                var divToPrint = document.getElementById("riderTable").cloneNode(true);
                var newWin = window.open("");
                newWin.document.write('<html><head><title>Print Rider List</title>');
                newWin.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
                newWin.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">');
                newWin.document.write('<style>');
                newWin.document.write('.header { display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }');
                newWin.document.write('.header img { margin-right: 25px; }');
                newWin.document.write('</style>');
                newWin.document.write('</head><body>');
                newWin.document.write('<div class="header"><img src="dist/img/images1.png" alt="Logo" width="90" height="90"><h1>Rider List</h1></div>');
                newWin.document.write(divToPrint.outerHTML);
                newWin.document.write('</body></html>');
                newWin.document.close();
                newWin.print();
                actionsColumn.forEach(function(cell) {
                    cell.style.display = '';
                });
                document.getElementById('printButton').style.display = 'inline-block';
                if (dataTablePagination) {
                    dataTablePagination.style.display = 'block';
                }
                if (dataTableLengthSelector) {
                    dataTableLengthSelector.style.display = 'block';
                }
            };

            // Search Functionality
            document.getElementById('searchInput').addEventListener('keyup', function() {
                var value = this.value.toLowerCase();
                document.querySelectorAll('table tbody tr').forEach(function(row) {
                    row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
                });
            });
        });
    </script>
</body>
</html>
