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
    <!-- add customer-->
    <div class="class="float-right mb-3" role="group" style="float:right;">
    <button class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal"> <i class="fas fa-plus"></i> Add</button> <br> <br> 
    <!-- Print Button -->
    <div class="text-right mb-3">
         <!-- <input class="form-control no-print" id="searchInput" type="text" placeholder="Search.."> -->

         <button id="printButton" class="btn btn-success no-print"  onclick="printCustomerList()" style="float: right;">Print List</button>
    </div>

    <!-- Customer Table -->
    <table id="customerTable" class="table table-bordered table-responsive-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Last Name</th>
               <!-- <th>Company</th>-->
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
                  <!--  <td><?= htmlspecialchars($customer['company']) ?></td> -->
                    <td><?= htmlspecialchars($customer['address']) ?></td>
                    <td><?= htmlspecialchars($customer['contact']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td>
                        <div class="btn-group-vertical" role="group">
                            <!-- <button class="btn btn-success" data-toggle="modal" data-target="#addCustomerModal">
                                <i class="fas fa-plus"></i> Add
                            </button> -->
                            <button class="btn btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($customer)) ?>)">
                                <i class="fas fa-edit"></i> Edit</button>
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
    if (confirm('Are you sure you want to delete this customer?')) {
        $.ajax({
            type: 'POST',
            url: 'delete_customer.php', // Path to your delete script
            data: { id: id },
            success: function(response) {
                try {
                    var data = JSON.parse(response); // Parse the JSON response
                    if (data.success) {
                        alert(data.message); // Show success message
                        location.reload(); // Reload the page to see the changes
                    } else {
                        alert('Error: ' + data.message); // Show error message
                    }
                } catch (e) {
                    alert('Error parsing response: ' + e.message); // Handle parsing errors
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log response text for debugging
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
