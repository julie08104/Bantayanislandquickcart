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
                            <button class="btn btn-info btn-sm" onclick="openViewModal(<?= htmlentities(json_encode($rider)) ?>)">
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
                    <h5 class="modal-title" id="viewRiderModalLabel">View Rider Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="view_name">Name:</label>
                        <p id="view_name"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_lastname">Last Name:</label>
                        <p id="view_lastname"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_gender">Gender:</label>
                        <p id="view_gender"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_address">Address:</label>
                        <p id="view_address"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_contact_number">Contact Number:</label>
                        <p id="view_contact_number"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_email">Email:</label>
                        <p id="view_email"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_vehicle_type">Vehicle Type:</label>
                        <p id="view_vehicle_type"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_license_number">License Number:</label>
                        <p id="view_license_number"></p>
                    </div>
                    <div class="form-group">
                        <label for="view_status">Status:</label>
                        <p id="view_status"></p>
                    </div>
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
                        <input type="hidden" id="editRiderId" name="rider_id">
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
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
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
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
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

            // Open view modal
            window.openViewModal = function(rider) {
                $('#view_name').text(rider.name);
                $('#view_lastname').text(rider.lastname);
                $('#view_gender').text(rider.gender);
                $('#view_address').text(rider.address);
                $('#view_contact_number').text(rider.contact_number);
                $('#view_email').text(rider.email);
                $('#view_vehicle_type').text(rider.vehicle_type);
                $('#view_license_number').text(rider.license_number);
                $('#view_status').text(rider.status);
                $('#viewRiderModal').modal('show');
            };

            // Open edit modal
            window.openEditModal = function(rider) {
                $('#editRiderId').val(rider.rider_id);
                $('#edit_name').val(rider.name);
                $('#edit_lastname').val(rider.lastname);
                $('#edit_gender').val(rider.gender);
                $('#edit_address').val(rider.address);
                $('#edit_contact_number').val(rider.contact_number);
                $('#edit_email').val(rider.email);
                $('#edit_vehicle_type').val(rider.vehicle_type);
                $('#edit_license_number').val(rider.license_number);
                $('#edit_status').val(rider.status);
                $('#editRiderModal').modal('show');
            };
        });

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
