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
    $stmt = $pdo->prepare("INSERT INTO customers (name, lastname, address, contact, email) VALUES (?, ?, ?, ?, ?)");
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

function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
    return $stmt->execute([$id]);
}

// Fetch customers for display
$customers = readCustomers();
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
        <!-- Customer List Heading -->
        <br> <br> <br>
        <h1>Customer List</h1>
    </div>
<!--add customer-->
    <div class="container-fluid" style="margin-left: 0px!important;"> 
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
                                <button class="btn btn-danger" onclick="confirmDelete(<?= htmlspecialchars($customer['id']) ?>)">
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
    console.log('Attempting to delete customer with ID:', id); // Debugging log
    $.ajax({
        url: 'delete_customer.php',
        method: 'POST',
        data: { id: id },
        success: function(response) {
            console.log('Server response:', response); // Debugging log
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
        },
        error: function(xhr, status, error) {
            console.log('AJAX error:', status, error); // Debugging log
            Swal.fire(
                'Error!',
                'There was an error processing your request.',
                'error'
            );
        }
    });
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
    var actionsHeader = document.querySelector('#customerTable th:nth-last-child(1)'); // Assuming Actions is the last column
    var actionsCells = document.querySelectorAll('#customerTable td:nth-last-child(1)'); // Assuming Actions is the last column
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
    }, 1000); // Ensure styles are applied before printing
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

