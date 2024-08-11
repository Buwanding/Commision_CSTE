<?php
// Ensure database connection is included
require '../php/db.php';

// Check if file_path is set
if (isset($_GET['file_path'])) {
    $file_path = $_GET['file_path'];

    // Sanitize the file path to avoid security issues
    $file_path = basename($file_path);

    // Define the full path to the file
    $full_path = './uploads/' . $file_path;

    // Check if the file exists
    if (file_exists($full_path)) {
        // Set headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_path . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($full_path));

        // Read the file and output it
        readfile($full_path);
        exit;
    } else {
        echo 'File not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
