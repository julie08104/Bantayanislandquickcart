<?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $class = $message['type'] == 'success' ? "p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" : 'p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50';
        echo "<div class='message $class'>{$message['text']}</div>";
        unset($_SESSION['message']);
    }
?>