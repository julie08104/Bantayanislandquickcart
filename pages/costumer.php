<?php
function addColumnIfNotExists($pdo, $table, $column, $columnDefinition) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM $table LIKE ?");
    $stmt->execute([$column]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result === false) {
        // Column does not exist, so add it
        $stmt = $pdo->prepare("ALTER TABLE $table ADD COLUMN $column $columnDefinition");
        $stmt->execute();
    }
}

// Create Customer
function createCustomer($name, $lastname, $address, $contact, $email) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO customer (name, lastname, address, contact, email) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $lastname, $address, $contact, $email]);
}

// Read Customers
function readCustomers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM customer");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result === false) {
        return [];
    }
    return $result;
}

// Update Customer
function updateCustomer($id, $name, $lastname, $address, $contact, $email) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE customer SET name = ?, lastname = ?, address = ?, contact = ?, email = ? WHERE id = ?");
    return $stmt->execute([$name, $lastname, $address, $contact, $email, $id]);
}

// Delete Customer
function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
    return $stmt->execute([$id]);
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'create':
            createCustomer($_POST['name'], $_POST['lastname'], $_POST['address'], $_POST['contact'], $_POST['email']);
            break;
        case 'update':
            updateCustomer($_POST['id'], $_POST['name'], $_POST['lastname'], $_POST['address'], $_POST['contact'], $_POST['email']);
            break;
        case 'delete':
            deleteCustomer($_POST['id']);
            break;
    }
    header('Location: index.php?page=customer'); // Redirect after action
    exit;
}

// Fetch customers for display
$customers = readCustomers();
if ($customers === false) {
    $customers = []; // Default to empty array if error occurred
}
?>
<style>
    /* Initially hide the print image */
    #printImage {
        display: none;
    }

    @media print {
        .print-container {
            display: flex;
            align-items: center; /* Align items horizontally */
            position: relative;
        }

        .print-only {
            display: block !important;
            width: 60px; /* Adjust the width to make the image smaller */
            height: auto;
            z-index: 10;
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

        .brand-image {
            height: 70px;
            width: 70px;
        }
    }
</style>
</head>
<body>
   <!-- Print Image and Heading -->
    <div class="print-container">
        <div id="printImage" class="print-only">
            <img src="dist/img/images1.png" alt="logo" class="brand-image">
        </div>
        <br><br><br>
        <h1>Customer List</h1>
    </div>

    <div class="container-fluid" style="margin-left: 0px!important;">
        <!-- Add Customer Button -->
        <div class="float-left mb-3">
            <button class="btn btn-success no-print" data-toggle="modal" data-target="#addCustomerModal">
                <i class="fas fa-plus"></i> Add Customer
            </button>
        </div>
<!-- Print Button and Search Input -->
<div class="container-fluid">
    <div class="row">
        <div class="col text-right">
            <div class="mb-3">
                <!-- Print Button -->
                <button id="printButton" class="btn btn-success no-print" onclick="printCustomerList()">Print List</button>
            </div>
              <div class="float-right mb-3">
                <!-- Search Input -->
                <input class="form-control form-control-sm no-print" id="searchInput" type="text" placeholder="Search.." style="width: 200px;">
            </div>
        </div>
    </div>
</div>








        <!-- Customer Table -->
        <table id="customerTable" class="table table-bordered table-responsive-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php
                    $counter = 1; // Initialize counter variable
                    foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($customer['name']) ?></td>
                            <td><?= htmlspecialchars($customer['lastname']) ?></td>
                            <td><?= htmlspecialchars($customer['address']) ?></td>
                            <td><?= htmlspecialchars($customer['contact']) ?></td>
                            <td><?= htmlspecialchars($customer['email']) ?></td>
                            <td>
                                <div class="btn-group-vertical" role="group">
                                    <button class="btn btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($customer)) ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger" onclick="confirmDelete(<?= $customer['id'] ?>)">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Customer</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addCustomerForm" method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name:</label>
                            <input type="text" class="form-control" name="lastname" required>
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <textarea class="form-control" name="address"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <input type="text" class="form-control" name="contact">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Add Customer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Customer</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" id="edit_customer_id" name="id">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" id="edit_name" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name:</label>
                            <input type="text" id="edit_lastname" class="form-control" name="lastname" required>
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <textarea id="edit_address" class="form-control" name="address"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <input type="text" id="edit_contact" class="form-control" name="contact">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" id="edit_email" class="form-control" name="email" required>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="submitEditForm()">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery, Bootstrap, and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#customerTable').DataTable({
            "lengthMenu": [10, 20, 50, 100]
        });
    });

    function openEditModal(customer) {
        $('#editCustomerModal').modal('show');
        $('#edit_customer_id').val(customer.id);
        $('#edit_name').val(customer.name);
        $('#edit_lastname').val(customer.lastname);
        $('#edit_address').val(customer.address);
        $('#edit_contact').val(customer.contact);
        $('#edit_email').val(customer.email);
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure you want to delete this customer?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteCustomer(id);
            }
        });
    }

    function deleteCustomer(id) {
        $.ajax({
            url: 'delete_customer.php',
            method: 'POST',
            data: { id: id },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.success) {
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Refresh the page to update the table
                        });
                    } else {
                        Swal.fire(
                            'Failed!',
                            data.message,
                            'error'
                        );
                    }
                } catch (e) {
                    Swal.fire(
                        'Error!',
                        'Invalid server response format.',
                        'error'
                    );
                }
            },
            error: function(xhr, status, error) {
                Swal.fire(
                    'Error!',
                    'There was an error processing your request.',
                    'error'
                );
            }
        });
    }

    function submitEditForm() {
        var formData = $('#editCustomerForm').serialize();
        
        $.ajax({
            type: 'POST',
            url: 'update_customer.php',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    window.location.href = 'index.php?page=customer';
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function() {
                alert('Error updating customer. Please try again.');
            }
        });
    }

    function submitAddForm() {
        var formData = $('#addCustomerForm').serialize();
        
        $.ajax({
            type: 'POST',
            url: 'add_customer.php',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    window.location.href = 'index.php?page=customer';
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function() {
                alert('Error adding customer. Please try again.');
            }
        });
    }

 function printCustomerList() {
    console.log("Print function called");         

    // Hide action buttons and action column
    var buttons = document.querySelectorAll('#customerTable tbody button');
    var actionsHeader = document.querySelector('#customerTable th:nth-child(8)');
    var actionsCells = document.querySelectorAll('#customerTable td:nth-child(8)');

    // Hide elements
    buttons.forEach(function(button) {
        button.style.display = 'none';
    });
    if (actionsHeader) {
        actionsHeader.style.display = 'none';
    }
    actionsCells.forEach(function(cell) {
        cell.style.display = 'none';
    });

    // Hide DataTables elements
    var dataTablePagination = document.querySelector('.dataTables_paginate');
    var dataTableLengthSelector = document.querySelector('.dataTables_length');
    var searchInput = document.querySelector('.dataTables_filter');
    var printButton = document.getElementById('printButton');

    if (dataTablePagination) {
        dataTablePagination.style.display = 'none';
    }
    if (dataTableLengthSelector) {
        dataTableLengthSelector.style.display = 'none';
    }
    if (searchInput) {
        searchInput.style.display = 'none';
    }
    if (printButton) {
        printButton.style.display = 'none';
    }

    // Use setTimeout to ensure the styles are applied before printing
    setTimeout(function() {
        console.log("Elements hidden, initiating print");
        window.print();

        console.log("Print initiated");

        // Restore elements after printing
        buttons.forEach(function(button) {
            button.style.display = 'inline-block';
        });
        if (actionsHeader) {
            actionsHeader.style.display = 'table-cell';
        }
        actionsCells.forEach(function(cell) {
            cell.style.display = 'table-cell';
        });
        if (dataTablePagination) {
            dataTablePagination.style.display = 'block';
        }
        if (dataTableLengthSelector) {
            dataTableLengthSelector.style.display = 'block';
        }
        if (searchInput) {
            searchInput.style.display = 'inline-block'; // Ensure display is restored
        }
        if (printButton) {
            printButton.style.display = 'inline-block';
        }

        console.log("Elements restored after printing");
    }, 500); // Adjust the delay as needed
}

    document.getElementById('searchInput').addEventListener('keyup', function() {
        var value = this.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(function(row) {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.style.display = 'block'; // Ensure the search input is displayed
        }
    });
    </script>
</body>
</html>
