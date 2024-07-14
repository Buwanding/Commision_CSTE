<?php
// Ensure database connection is included
require 'db.php';

$file_id = $_REQUEST['file_id'];

// Fetch file details based on file_id
$file_query = "SELECT student_file FROM activity_details WHERE id = ?";
$file_stmt = $conn->prepare($file_query);
$file_stmt->bind_param("i", $file_id);
$file_stmt->execute();
$file_result = $file_stmt->get_result();
$file = $file_result->fetch_assoc();
$file_stmt->close();

if ($file) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="student_file_' . $file_id . '"');
    echo $file['student_file'];
} else {
    echo "File not found.";
}
?>
