<?php
require '../php/db.php';

$activity_id = $_POST['activity_id'];
$student_email = $_POST['student_email'];
$timepass = $_POST['timepass'];

// Check if the student already passed the activity
$passed_query = "SELECT COUNT(*) AS count FROM activity_details WHERE activity_id = ? AND student_email = ?";
$passed_stmt = $conn->prepare($passed_query);
$passed_stmt->bind_param("is", $activity_id, $student_email);
$passed_stmt->execute();
$passed_result = $passed_stmt->get_result();
$passed = $passed_result->fetch_assoc()['count'];
$passed_stmt->close();

if ($passed > 0) {
    // Redirect or inform the user that they have already passed the activity
    header("Location: already_passed.php"); // Create a page to inform the user
    exit;
}

if (isset($_FILES['student_file']) && $_FILES['student_file']['error'] == 0) {
    // Get file content
    $student_file = file_get_contents($_FILES['student_file']['tmp_name']);
    $status = "done";

    // Insert file into the database
    $insert_query = "INSERT INTO activity_details (activity_id, student_email, student_file, timepass, status) VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("issss", $activity_id, $student_email, $student_file, $timepass, $status);

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
?>
