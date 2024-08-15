<?php
session_start(); // Start the session to access session variables.

if (!isset($_SESSION['username'])) { // Check if the user is logged in by checking the session.
    header("Location: index.html"); // If not logged in, redirect to the login page.
    exit(); // Stop script execution.
}

require 'db.php'; // Include the database connection.

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if the form has been submitted via POST.
    // Retrieve form data and assign it to variables.
    $subject_id = $_POST['subject_id'];
    $activity_name = $_POST['activity_name'];
    $description = $_POST['description'];
    $deadline = $_POST["deadline"];

    // Prepare an SQL statement to insert the activity into the database.
    $sql = "INSERT INTO activities (subject_id, activity_name, description, deadline) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); // Prepare the SQL query.
    $stmt->bind_param("isss", $subject_id, $activity_name, $description, $deadline); // Bind the parameters to the SQL query.

    if ($stmt->execute()) { // Execute the query and check if it was successful.
        echo "Activity added successfully!";
    } else {
        echo "Error adding activity: " . $stmt->error;
    }

    $stmt->close(); // Close the statement.

    // Store form data in session variables.
    $_SESSION['subject_id'] = $subject_id;
    $_SESSION['activity_name'] = $activity_name;
    $_SESSION['deadline'] = $deadline;

    // Include another script to handle messaging.
    require "sendmessage.php";
    exit(); // Stop script execution.
}
?>
