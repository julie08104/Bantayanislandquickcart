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
            <h1 class="text-2xl">Store List</h1>
            <div class="space-x-2">
                <a href="store-new.php" class="no-print text-sm px-4 py-2 border rounded bg-blue-700 text-white">Create</a>
                <!-- <button class="no-print text-sm px-4 py-2 border rounded" onclick="window.print()">Print</button> -->
            </div>
        </div>
        <table id="userTable" class="display responsive">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
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
            "ajax": "php/fetch_stores.php",
            "columns": [
                { "data": "name" },
                { "data": "location" },
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
            window.location.href="store-edit.php?id="+data.id;
        });

        $('#userTable tbody').on('click', '.delete-btn', function() {
            var data = $('#userTable').DataTable().row($(this).parents('tr')).data();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete store with ID: ' + data.id,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'php/delete_store.php',
                        type: 'POST',
                        data: { id: data.id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Store has been deleted.',
                                    'success'
                                );
                                $('#userTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the store.',
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
                        'The store is safe :)',
                        'info'
                    );
                }
            });
        });
    });
</script>
<?php include '../footer.php'; ?>
