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
            <h1 class="text-2xl">Rider List</h1>
            <div class="space-x-2">
                <a href="raider-new.php" class="no-print text-sm px-4 py-2 border rounded bg-blue-700 text-white">Create</a>
                <!-- <button class="no-print text-sm px-4 py-2 border rounded" onclick="window.print()">Print</button> -->
            </div>
        </div>
        <table id="userTable" class="display responsive">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Number</th>
                    <th>Verified</th>
                    <th class="no-print">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Add your CSS here -->
<style>
    /* Styling for action buttons (Edit and Delete) */
    .action-buttons {
        display: flex;
        gap: 10px; /* Space between buttons */
    }

    /* Edit Button Styling */
    .edit-btn {
        background-color: #4CAF50; /* Green background */
        color: white; /* White text */
        padding: 8px 16px; /* Adjust button padding */
        border-radius: 5px; /* Rounded corners */
        border: 2px solid transparent; /* Transparent border for initial state */
        font-size: 14px;
        font-weight: bold;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    }

    /* Edit Button Hover Effect */
    .edit-btn:hover {
        background-color: #388E3C; /* Darker green on hover */
        border-color: #2E7D32; /* Darker green border */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
    }

    /* Delete Button Styling */
    .delete-btn {
        background-color: #F44336; /* Red background */
        color: white; /* White text */
        padding: 8px 16px; /* Adjust button padding */
        border-radius: 5px; /* Rounded corners */
        border: 2px solid transparent; /* Transparent border for initial state */
        font-size: 14px;
        font-weight: bold;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    }

    /* Delete Button Hover Effect */
    .delete-btn:hover {
        background-color: #D32F2F; /* Darker red on hover */
        border-color: #B71C1C; /* Darker red border */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
    }
</style>

<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "responsive": true,
            "scrollX": true,
            "ajax": "php/fetch_raiders.php",
            "columns": [
                { "data": "fullname" },
                { "data": "email" },
                { "data": "phone" },
                { "data": "address" },
                { "data": "vehicle_type" },
                { "data": "vehicle_number" },
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
            window.location.href="raider-edit.php?id="+data.id;
        });

        $('#userTable tbody').on('click', '.delete-btn', function() {
            var data = $('#userTable').DataTable().row($(this).parents('tr')).data();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete rider with ID: ' + data.id,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'php/delete_raider.php',
                        type: 'POST',
                        data: { id: data.id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Rider has been deleted.',
                                    'success'
                                );
                                $('#userTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the rider.',
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
                        'The rider is safe :)',
                        'info'
                    );
                }
            });
        });
    });
</script>

<?php include '../footer.php'; ?>
