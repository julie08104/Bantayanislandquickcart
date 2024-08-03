<?php
function addColumnIfNotExists($pdo, $table, $column, $columnDefinition) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$column]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result === false) {
        // Column does not exist, so add it
        $stmt = $pdo->prepare("ALTER TABLE `$table` ADD COLUMN `$column` $columnDefinition");
        $stmt->execute();
    }
}

// Create Customer
function createCustomer($name, $lastname, $address, $contact, $email) {
    global $pdo;
    // Prepare the SQL statement with placeholders
    $stmt = $pdo->prepare("INSERT INTO customer (name, lastname, address, contact, email) VALUES (?, ?, ?, ?, ?)");
    // Execute the statement with the provided values
    return $stmt->execute([$name, $lastname, $address, $contact, $email]);
}

// Read Customers
function readCustomers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM customer");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    header('Location: index.php?page=costumer'); // Redirect after action
    exit;
}
// Fetch customers for display
$customers = readCustomers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
    <link rel="stylesheet" href="path/to/datatables.css">
    <style>
@media print {
        .print-only {
            display: block !important;
            position: fixed;
            top: 10px;
            left: 10px;
            width: 100px;
            height: auto;
            z-index: 1000;
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
   <!-- Print Image -->
<div id="printImage" class="print-only">
    <img src="dist/img/images1.png" alt="logo" class="brand-image" style="display: block; margin: 5px auto; width: 180px; height: auto;">
</div>

<!-- Customer Table and other content -->

    <div class="container-fluid" style="margin-left: 0px!important;">
        <h1>Customer List</h1>
        <!-- Add Customer Button -->
        <div class="float-left mb-3">
            <button class="btn btn-success no-print" data-toggle="modal" data-target="#addCustomerModal">
                <i class="fas fa-plus"></i> Add Customer
            </button>
        </div>
        <!-- Print Button -->
        <div class="text-right mb-3">
            <button id="printButton" class="btn btn-success no-print" onclick="printCustomerList()" style="float: right;">Print List</button>
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
                                <button class="btn btn-danger" onclick="deleteCustomer(<?= htmlspecialchars($customer['id']) ?>)">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Customer Modal -->
    <!-- Your modal HTML -->

    <!-- Edit Customer Modal -->
    <!-- Your modal HTML -->

    <script src="path/to/jquery.js"></script>
    <script src="path/to/bootstrap.js"></script>
    <script src="path/to/datatables.js"></script>
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

    function deleteCustomer(id) {
        if (confirm('Are you sure you want to delete this customer?')) {
            $.ajax({
                type: 'POST',
                url: 'delete_customer.php', // Make sure this URL is correct
                data: { action: 'delete', id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload(); // Refresh the page to reflect changes
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error deleting customer. Please try again.');
                }
            });
        }
    }

    function submitEditForm() {
        var formData = $('#editCustomerForm').serialize(); // Gather form data
        
        $.ajax({
            type: 'POST',
            url: 'update_customer.php', // Path to your PHP update script
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    window.location.href = 'index.php?page=customer'; // Redirect after success
                } else {
                    alert('Error: ' + data.message); // Show error message
                }
            },
            error: function() {
                alert('Error updating customer. Please try again.');
            }
        });
    }

    function submitAddForm() {
        var formData = $('#addCustomerForm').serialize(); // Gather form data
        
        $.ajax({
            type: 'POST',
            url: 'add_customer.php', // Path to your PHP add script
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    window.location.href = 'index.php?page=customer'; // Redirect after success
                } else {
                    alert('Error: ' + data.message); // Show error message
                }
            },
            error: function() {
                alert('Error adding customer. Please try again.');
            }
        });
    }

function printCustomerList() {
    // Show the print image
    var printImage = document.getElementById('printImage');
    if (printImage) {
        printImage.style.display = 'block';
    }

    // Hide elements that should not appear in the print view
    var buttons = document.querySelectorAll('#customerTable tbody button');
    var actionsHeader = document.querySelector('#customerTable th:nth-child(7)');
    var actionsCells = document.querySelectorAll('#customerTable td:nth-child(7)');
    var dataTablePagination = document.querySelector('.dataTables_paginate');
    var dataTableLengthSelector = document.querySelector('.dataTables_length');
    var searchInput = document.querySelector('.dataTables_filter');
    var printButton = document.getElementById('printButton');

    if (buttons.length > 0) {
        buttons.forEach(button => button.style.display = 'none');
    }
    if (actionsHeader) {
        actionsHeader.style.display = 'none';
    }
    actionsCells.forEach(cell => cell.style.display = 'none');
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
        window.print();

        // Restore elements after printing
        if (buttons.length > 0) {
            buttons.forEach(button => button.style.display = 'inline-block');
        }
        if (actionsHeader) {
            actionsHeader.style.display = 'table-cell';
        }
        actionsCells.forEach(cell => cell.style.display = 'table-cell');
        if (dataTablePagination) {
            dataTablePagination.style.display = 'block';
        }
        if (dataTableLengthSelector) {
            dataTableLengthSelector.style.display = 'block';
        }
        if (searchInput) {
            searchInput.style.display = '';
        }
        if (printButton) {
            printButton.style.display = 'inline-block';
        }
        if (printImage) {
            printImage.style.display = 'none';
        }
    }, 1000); // Adjust the delay if necessary
}


            console.log("Elements restored after printing");
        }, 1000); // Increased delay to ensure elements are hidden
    }
    </script>
</body>
</html>
