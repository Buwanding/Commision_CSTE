<?php
require '../php/db.php'; // Include the database connection.

if (isset($_GET['file_path'])) { // Check if the file path is provided via GET.
    $file_path = $_GET['file_path'];

    $file_path = basename($file_path); // Sanitize the file path to avoid security issues.

    $full_path = './uploads/' . $file_path; // Define the full path to the file.

    if (file_exists($full_path)) { // Check if the file exists.
        // Set headers to force the download of the file.
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_path . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($full_path));

        readfile($full_path); // Read the file and output it to the browser.
        exit; // Stop script execution.
    } else {
        echo 'File not found.'; // Output an error message if the file doesn't exist.
    }
} else {
    echo 'Invalid request.'; // Output an error message if no file path is provided.
}
?>
