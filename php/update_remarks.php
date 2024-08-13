<?php
session_start();
require '../php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ad_id"])) {
    $ad_id = $_POST["ad_id"];
    $student_email = $_POST['student_email'];
    $remarks = $_POST["remarks"];
    $activity_name = $_POST['activity_name'];
    $subject_name = $_POST['subject_name'];

    // Prepare and bind the update statement
    $stmt = $conn->prepare("UPDATE activity_details SET remarks = ? WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $remarks, $ad_id);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "<script>alert('Remarks updated successfully!');</script>";

        // Set session variables
        $_SESSION['activity_id'] = $ad_id;
        $_SESSION['student_email'] = $student_email;
        $_SESSION['remarks'] = $remarks;
        $_SESSION['activity_name'] = $activity_name;
        $_SESSION['subject_name'] = $subject_name;

        // Redirect to another page
        header("Location: text_remarks.php");
        exit();
    } else {
        echo "Error updating remarks: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
