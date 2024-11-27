<?php
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
                        <button class="edit-btn">Edit</button>
                        <button class="delete-btn">Delete</button>
                    `,
                    "className": "action-buttons"
                }
            ],
            layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        'colvis'
                    ]
                }
            }
        });

        // Handle Edit and Delete button clicks
        $('#userTable tbody').on('click', '.edit-btn', function() {
            var data = $('#userTable').DataTable().row($(this).parents('tr')).data();
            window.location.href="customer-edit.php?id="+data.id;
        });

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
<?php include '../footer.php'; ?>