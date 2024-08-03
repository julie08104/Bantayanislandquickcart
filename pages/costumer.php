<?php
// Database connection setup
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u510162695_ample'; // Update with your password
', 'usernameu510162695_ample', '1Ample_database');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

function addColumnIfNotExists($pdo, $table, $column, $columnDefinition) {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$column]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result === false) {
        $stmt = $pdo->prepare("ALTER TABLE `$table` ADD COLUMN `$column` $columnDefinition");
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
    header('Location: index.php?page=customer'); // Redirect after action
    exit;
}

// Fetch customers for display
$customers = readCustomers();
?>


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
<!-- HTML and Bootstrap Front-end -->
<br>
<div class="container-fluid" style="margin-left: 0px!important;">
    <h1>Customer List</h1>
        <!-- Add Customer Button -->
        <div class="float-left mb-3" role="group">
            <button class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal">
                <i class="fas fa-plus"></i> Add Customer
            </button>
        </div>

        <!-- Print Button -->
        <div class="text-right mb-3">
            <button id="printButton" class="btn btn-success no-print" onclick="printCustomerList()">Print List</button>
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
                    <th>Actions</th> <!-- Include Actions header -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['id']) ?></td>
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
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name:</label>
                            <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <textarea class="form-control" id="edit_address" name="address"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <input type="text" class="form-control" id="edit_contact" name="contact">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                url: 'index.php', // Make sure the URL matches the location where your PHP script is processing
                data: { action: 'delete', id: id },
                success: function(response) {
                    alert('Customer deleted successfully.');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error deleting customer. Please try again.');
                }
            });
        }
    }

    function printCustomerList() {
        var buttons = document.querySelectorAll('#customerTable tbody button');
        buttons.forEach(function(button) {
            button.style.display = 'none';
        });

        var actionsHeader = document.querySelector('#customerTable th:nth-child(7)');
        var actionsCells = document.querySelectorAll('#customerTable td:nth-child(7)');
        if (actionsHeader) {
            actionsHeader.style.display = 'none';
        }
        actionsCells.forEach(function(cell) {
            cell.style.display = 'none';
        });

        var dataTablePagination = document.querySelector('.dataTables_paginate');
        if (dataTablePagination) {
            dataTablePagination.style.display = 'none';
        }

        var dataTableLengthSelector = document.querySelector('.dataTables_length');
        if (dataTableLengthSelector) {
            dataTableLengthSelector.style.display = 'none';
        }

        var searchInput = document.querySelector('.dataTables_filter');
        if (searchInput) {
            searchInput.style.display = 'none';
        }

        var printButton = document.getElementById('printButton');
        if (printButton) {
            printButton.style.display = 'none';
        }

        setTimeout(function() {
            window.print();

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
                searchInput.style.display = '';
            }

            if (printButton) {
                printButton.style.display = 'inline-block';
            }
        }, 1000);
    }
    </script>
</body>
</html>
