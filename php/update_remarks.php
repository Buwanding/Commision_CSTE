<?php
// Ensure database connection is included
require '../php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["activity_id"])) {
    $activity_id = $_POST["activity_id"];
    $remarks = $_POST["remarks"];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE activity_details SET remarks = ? WHERE id = ?");
    $stmt->bind_param("si", $remarks, $activity_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Remarks updated successfully!');</script>";
    } else {
        echo "Error updating remarks: " . $conn->error;
    }

    $stmt->close();

    // Redirect back to the activity details page
    header("Location: activity_details.php?activity_id=$activity_id");
    exit();
} else {
    echo "Invalid request.";
}
?>
