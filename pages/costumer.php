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
            createCustomer($_POST['name'], $_POST['lastname'], $_POST['company'], $_POST['address'], $_POST['contact'], $_POST['email']);
            break;
        case 'update':
            updateCustomer($_POST['id'], $_POST['name'], $_POST['lastname'], $_POST['company'], $_POST['address'], $_POST['contact'], $_POST['email']);
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
        <i class="fas fa-plus"></i> Add
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
                    <br><br>
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
        $('#edit_company').val(customer.company);
        $('#edit_address').val(customer.address);
        $('#edit_contact').val(customer.contact);
        $('#edit_email').val(customer.email);
    }

    function deleteCustomer(id) {
    // Show a confirmation dialog using SweetAlert2
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this customer?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, send an AJAX request to delete the customer
            $.ajax({
                type: 'POST',
                url: 'delete_customer.php',
                data: { id: id },
                success: function(response) {
                    try {
                        // Parse the JSON response
                        var data = JSON.parse(response);
                        if (data.success) {
                            // Show success message and reload the page
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            // Show error message if deletion was not successful
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    } catch (e) {
                        // Handle JSON parsing errors
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error parsing response: ' + e.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting customer. Please try again.'
                    });
                }
            });
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
        var buttons = document.querySelectorAll('#customerTable tbody button');
        buttons.forEach(function(button) {
            button.style.display = 'none';
        });

        var actionsHeader = document.querySelector('#customerTable th:nth-child(8)');
        var actionsCells = document.querySelectorAll('#customerTable td:nth-child(8)');
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

    document.getElementById('searchInput').addEventListener('keyup', function() {
        var value = this.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(function(row) {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.style.display = 'none';
        }
    });
    </script>
</body>
</html>