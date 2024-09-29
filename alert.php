<?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $message['type'] == 'success' ? 'success' : 'error';
        $text = $message['text'];

        echo "<script>
            Swal.fire({
                icon: '$type',
                title: '" . ($type == 'success' ? 'Success!' : 'Error!') . "',
                text: '$text',
                confirmButtonText: 'OK'
            });
        </script>";

        unset($_SESSION['message']);
    }
?>
