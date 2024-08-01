<?php
// Existing PHP code
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
function createCustomer($name, $lastname, $company, $address, $contact, $email) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO customer (name, lastname, company, address, contact, email) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $lastname, $company, $address, $contact, $email]);
}

// Read Customers
function readCustomers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM customer");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update Customer
function updateCustomer($id, $name, $lastname, $company, $address, $contact, $email) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE customer SET name = ?, lastname = ?, company = ?, address = ?, contact = ?, email = ? WHERE id = ?");
    return $stmt->execute([$name, $lastname, $company, $address, $contact, $email, $id]);
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
            if (createCustomer($_POST['name'], $_POST['lastname'], $_POST['company'], $_POST['address'], $_POST['contact'], $_POST['email'])) {
                header('Location: index.php?page=customer'); // Redirect after action
                exit;
            } else {
                echo "Error adding customer.";
            }
            break;
        case 'update':
            if (updateCustomer($_POST['id'], $_POST['name'], $_POST['lastname'], $_POST['company'], $_POST['address'], $_POST['contact'], $_POST['email'])) {
                header('Location: index.php?page=customer'); // Redirect after action
                exit;
            } else {
                echo "Error updating customer.";
            }
            break;
        case 'delete':
            if (deleteCustomer($_POST['id'])) {
                header('Location: index.php?page=customer'); // Redirect after action
                exit;
            } else {
                echo "Error deleting customer.";
            }
            break;
    }
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <style>
        @media print {
            .no-print,
            .dataTables_filter,
            .dataTables_paginate,
            .dataTables_length,
            #customerTable th:nth-child(8),
            #customerTable td:nth-child(8) {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="margin-left: 0px!important;">
        <h1>Customer List</h1>

        <!-- Print Button -->
        <div class="text-right mb-3">
            <input class="form-control no-print" id="searchInput" type="text" placeholder="Search..">
            <button id="printButton" class="btn btn-success no-print" onclick="printCustomerList()" style="float: right;">Print List</button>
        </div>

        <!-- Customer Table -->
        <table id="customerTable" class="table table-bordered table-responsive-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th>Company</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th> <!-- Include Actions header -->
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
                        <td><?= htmlspecialchars($customer['company']) ?></td>
                        <td><?= htmlspecialchars($customer['address']) ?></td>
                        <td><?= htmlspecialchars($customer['contact']) ?></td>
                        <td><?= htmlspecialchars($customer['email']) ?></td>
                        <td>
                            <div class="btn-group-vertical" role="group">
                                <button class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                                <button class="btn btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($customer)) ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger" onclick="deleteCustomer(<?= $customer['id'] ?>)">
                                    <i class="fas fa-trash-alt"> Delete</i>
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
                        <label>Company:</label>
                        <input type="text" class="form-control" name="company">
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
                            <label>Company:</label>
                            <input type="text" class="form-control" id="edit_company" name="company">
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
                        <button type="submit" class="btn btn-primary btn-sm">Update Customer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add your JavaScript links here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customerTable').DataTable({
                "lengthMenu": [10, 20, 50, 100]
            });
        });

        function printCustomerList() {
            console.log("Print function called");

            // Hide all buttons in the table body
            var buttons = document.querySelectorAll('#customerTable tbody button');
            buttons.forEach(function(button) {
                button.style.display = 'none';
            });

            // Hide the Actions column header and cells
            var actionsHeader = document.querySelector('#customerTable th:nth-child(8)');
            var actionsCells = document.querySelectorAll('#customerTable td:nth-child(8)');
            if (actionsHeader) {
                actionsHeader.style.display = 'none';
            }
            actionsCells.forEach(function(cell) {
                cell.style.display = 'none';
            });

            // Hide DataTables pagination
            var dataTablePagination = document.querySelector('.dataTables_paginate');
            if (dataTablePagination) {
                dataTablePagination.style.display = 'none';
            }

            // Hide DataTables table length selector
            var dataTableLengthSelector = document.querySelector('.dataTables_length');
            if (dataTableLengthSelector) {
                dataTableLengthSelector.style.display = 'none';
            }

            // Hide the search bar
            var searchInput = document.querySelector('.dataTables_filter');
            if (searchInput) {
                searchInput.style.display = 'none';
            }

            // Optionally, hide the "Print List" button itself
            var printButton = document.getElementById('printButton');
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
                    searchInput.style.display = '';
                }

                if (printButton) {
                    printButton.style.display = 'inline-block';
                }

                console.log("Elements restored after printing");
            }, 1000); // Increased delay to ensure elements are hidden
        }

        function openEditModal(customer) {
            document.getElementById('edit_customer_id').value = customer.id;
            document.getElementById('edit_name').value = customer.name;
            document.getElementById('edit_lastname').value = customer.lastname;
            document.getElementById('edit_company').value = customer.company;
            document.getElementById('edit_address').value = customer.address;
            document.getElementById('edit_contact').value = customer.contact;
            document.getElementById('edit_email').value = customer.email;
            $('#editCustomerModal').modal('show');
        }

        function deleteCustomer(id) {
            if (confirm('Are you sure you want to delete this customer?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                
                var actionField = document.createElement('input');
                actionField.type = 'hidden';
                actionField.name = 'action';
                actionField.value = 'delete';
                form.appendChild(actionField);
                
                var idField = document.createElement('input');
                idField.type = 'hidden';
                idField.name = 'id';
                idField.value = id;
                form.appendChild(idField);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
