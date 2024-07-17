<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_id = $_POST['activity_id'];
    $subject_id = $_POST['subject_id'];
    $student_email = $_POST['student_email'];
    $timepass = $_POST['timepass'];
    $student_file = $_FILES['student_file'];

    // Check if file was uploaded without errors
    if ($student_file['error'] == 0) {
        // Read the file content
        $file_content = file_get_contents($student_file['tmp_name']);

        // Prepare and bind
        $sql = "UPDATE activity_details SET student_file = ?, timepass = ? WHERE id = ? AND subject_id = ? AND student_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("bsisi", $file_content, $timepass, $activity_id, $subject_id, $student_email);

        // Execute the statement
        if ($stmt->execute()) {
            echo "File uploaded successfully!";
        } else {
            echo "Error uploading file: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $student_file['error'];
    }

    $conn->close();

    header("Location: ../student-page/dashboard.php");
    exit();
}
?>
