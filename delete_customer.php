function deleteCustomer(id) {
    console.log('Attempting to delete customer with ID:', id);
    $.ajax({
        url: 'delete_customer.php',
        method: 'POST',
        data: { id: id },
        success: function(response) {
            console.log('Server response:', response); // Log server response
            if (response === 'success') {
                Swal.fire(
                    'Deleted!',
                    'Customer deleted successfully.',
                    'success'
                ).then(() => {
                    location.reload(); // Refresh the page to update the table
                });
            } else {
                Swal.fire(
                    'Failed!',
                    'Customer not found or error occurred.',
                    'error'
                );
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX error:', status, error); // Log AJAX error
            console.log('Response text:', xhr.responseText); // Log the server response text
            Swal.fire(
                'Error!',
                'There was an error processing your request.',
                'error'
            );
        }
    });
}
