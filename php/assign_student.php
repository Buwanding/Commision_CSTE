<?php
session_start(); // Start the session.

if (!isset($_SESSION['username'])) { // Check if the user is logged in.
    header("Location: index.html"); // If not, redirect to the login page.
    exit(); // Stop script execution.
}

require 'db.php'; // Include the database connection.

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if the form has been submitted via POST.
    // Retrieve form data.
    $subject_id = $_POST['subject_id'];
    $student_email = $_POST['student_name'];

    // Prepare an SQL statement to insert the student assignment into the database.
    $sql = "INSERT INTO student_subjects (subject_id, student_email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql); // Prepare the SQL query.
    $stmt->bind_param("is", $subject_id, $student_email); // Bind the parameters to the SQL query.

    if ($stmt->execute()) { // Execute the query and check if it was successful.
        echo "Student assigned successfully!";
    } else {
        echo "Error assigning student: " . $stmt->error;
    }

    $stmt->close(); // Close the statement.
    $conn->close(); // Close the database connection.

    header("Location: ../teachers-page/dashboard.php"); // Redirect to the dashboard.
    exit(); // Stop script execution.
}
?>
