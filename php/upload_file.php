<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $target_file = $target_dir . basename($_FILES['student_file']['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a PDF
        if ($file_type != "pdf") {
            echo "Sorry, only PDF files are allowed.";
            exit;
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['student_file']['tmp_name'], $target_file)) {
            $status = "done";

            // Insert file path into the database
            $insert_query = "INSERT INTO activity_details (activity_id, student_email, student_file_path, timepass, status) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("issss", $activity_id, $student_email, $target_file, $timepass, $status);

            if ($insert_stmt->execute()) {
                // Redirect or inform the user of the successful upload
                header("Location: success_page.php");
                exit;
            } else {
                echo "Database Error: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        } else {
            echo "File Upload Error: Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file was uploaded or there was an error with the file upload.";
    }
} else {
    echo "Invalid request method.";
}
header("Location: ../student-page/dashboard.php");
exit();
?>
