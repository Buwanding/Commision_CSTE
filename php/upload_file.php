<?php
require '../php/db.php';

$activity_id = $_POST['activity_id'];
$student_email = $_POST['student_email'];
$timepass = $_POST['timepass'];

if(isset($_FILES['student_file']) && $_FILES['student_file']['error'] == 0) {
    // Get file content
    $student_file = file_get_contents($_FILES['student_file']['tmp_name']);
    
    // Insert file into the database
    $insert_query = "INSERT INTO activity_details (activity_id, student_email, student_file, timepass) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("isss", $activity_id, $student_email, $student_file, $timepass);
    
    if ($insert_stmt->execute()) {
        // Redirect or inform the user of the successful upload
        header("Location: success_page.php");
    } else {
        echo "Error: " . $insert_stmt->error;
    }
    $insert_stmt->close();
} else {
    echo "No file was uploaded or there was an error with the file upload.";
}
    header("Location: ../student-page/dashboard.php");
    exit();
// $conn->close();
?>

