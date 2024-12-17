<?php
    $page_type='admin';
    require '../config.php';
    require '../auth_check.php';
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="bg-white shadow rounded p-4 space-y-4">
        <?php include '../alert.php'; ?>
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl">Customer List</h1>
            <div class="space-x-2">
                <a href="customer-new.php" class="no-print text-sm px-4 py-2 border rounded bg-blue-700 text-white">Create</a>
                <!-- <button class="no-print text-sm px-4 py-2 border rounded" onclick="window.print()">Print</button> -->
            </div>
        </div>
        <table id="userTable" class="display responsive nowrap" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Verified</th>
                    <th class="no-print">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "responsive": true,
            "scrollX": true,
            "ajax": "php/fetch_customers.php",
            "columns": [
                { "data": "fullname" },
                { "data": "email" },
                { "data": "phone" },
                { "data": "address" },
                {
                    "data": "is_verified",
                    "render": function(data, type, row) {
                        return data == 1 ? 'Verified' : 'Not Verified';
                    }
                },
                {
                    "data": null,
                    "defaultContent": `
                        <button class="btn edit-btn">Edit</button>
                        <button class="btn delete-btn">Delete</button>
                    `,
                    "className": "action-buttons"
                }
            ]
        });

        // Handle Edit button clicks
        $('#userTable tbody').on('click', '.edit-btn', function() {
            var data = $('#userTable').DataTable().row($(this).parents('tr')).data();
            window.location.href = "customer-edit.php?id=" + data.id;
        });

        // Handle Delete button clicks
        $('#userTable tbody').on('click', '.delete-btn', function() {
            var data = $('#userTable').DataTable().row($(this).parents('tr')).data();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete customer with ID: ' + data.id,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'php/delete_customer.php',
                        type: 'POST',
                        data: { id: data.id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Customer has been deleted.',
                                    'success'
                                );
                                $('#userTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the customer.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'An AJAX error occurred.',
                                'error'
                            );
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'The customer is safe :)',
                        'info'
                    );
                }
            });
        });
    });
</script>

<!-- CSS to Style the Edit and Delete Buttons -->
<style>
    /* General Button Styles */
    .btn {
        border: 1px solid transparent;
        padding: 5px 10px;
        font-size: 14px;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    /* Edit Button Style */
    .edit-btn {
        background-color: #4CAF50; /* Green */
        border-color: #4CAF50;
    }

    .edit-btn:hover {
        background-color: #388E3C; /* Darker Green */
        border-color: #388E3C;
    }

    /* Delete Button Style */
    .delete-btn {
        background-color: #F44336; /* Red */
        border-color: #F44336;
    }

    .delete-btn:hover {
        background-color: #D32F2F; /* Darker Red */
        border-color: #D32F2F;
    }

    /* Action Buttons Container */
    .action-buttons {
        text-align: center;
    }

    /* Add spacing between buttons */
    .action-buttons .btn {
        margin: 2px;
    }
</style>
<?php include '../footer.php'; ?>
