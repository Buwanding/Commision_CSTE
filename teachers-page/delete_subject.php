<?php
// Check if session is not already started before starting one
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    exit();
}

require '../php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject_id'])) {
    $subject_id = $_POST['subject_id'];

    // Debugging output
    echo "Subject ID: " . htmlspecialchars($subject_id) . "<br>";
    echo "Username: " . htmlspecialchars($_SESSION['username']) . "<br>";

    // Delete the subject from the database
    $sql = "DELETE FROM subjects WHERE id = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $subject_id, $_SESSION['username']);
    $stmt->execute();

    // Debugging output
    echo "Affected Rows: " . $stmt->affected_rows . "<br>";

    if ($stmt->affected_rows > 0) {
        // If the deletion was successful, redirect to the dashboard
        header("Location: dashboard.php?message=Subject+deleted+successfully");
        exit();
    } else {
        // If the subject was not deleted, display an error message
        echo "Error deleting subject.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
