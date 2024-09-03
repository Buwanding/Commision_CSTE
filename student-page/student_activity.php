<?php

// Retrieve 'activity_name' and 'subject_name' from the request, defaulting to 'Unknown Activity' and 'Unknown Subject' if not set
$activity_name = isset($_REQUEST['activity_name']) ? $_REQUEST['activity_name'] : 'Unknown Activity';
$subject_name = isset($_REQUEST['subject_name']) ? $_REQUEST['subject_name'] : 'Unknown Subject';

session_start(); // Start the session to maintain state and access session variables
require '../php/db.php'; // Include the database connection script

// Ensure 'activity_id' is provided in the request, or terminate the script with an error message
if (isset($_REQUEST['activity_id'])) {
    $activity_id = $_REQUEST['activity_id'];
} else {
    die("Activity ID is missing."); // Display an error message and terminate the script if 'activity_id' is not provided
}

// Prepare an SQL query to fetch activity details based on the provided 'activity_id'
$activity_query = "SELECT student_email, remarks FROM activity_details WHERE activity_id = ? AND student_email = ?";
$activity_stmt = $conn->prepare($activity_query); // Prepare the SQL statement
$activity_stmt->bind_param("is", $activity_id, $_SESSION['username']); // Bind 'activity_id' and logged-in student email
$activity_stmt->execute(); // Execute the prepared statement
$activity_result = $activity_stmt->get_result(); // Retrieve the result set
$activity = $activity_result->fetch_assoc(); // Fetch the activity details
$activity_stmt->close(); // Close the prepared statement

// Get student email and current date
$student_email = $_SESSION['username'];
$timepass = date('Y-m-d');

// Prepare an SQL query to check if the student has already submitted this activity
$passed_query = "SELECT COUNT(*) AS count FROM activity_details WHERE activity_id = ? AND student_email = ?";
$passed_stmt = $conn->prepare($passed_query); // Prepare the SQL statement
$passed_stmt->bind_param("is", $activity_id, $student_email); // Bind 'activity_id' and student email
$passed_stmt->execute(); // Execute the prepared statement
$passed_result = $passed_stmt->get_result(); // Retrieve the result set
$passed = $passed_result->fetch_assoc()['count']; // Fetch the count of submissions
$passed_stmt->close(); // Close the prepared statement

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Make the webpage responsive -->
    <title>Upload File</title> <!-- Set the title of the webpage -->
    <link rel="stylesheet" href="./student-css/student-page.css"> <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to Font Awesome -->
</head>
<body>
<header>
    <div class="header-container">
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> <!-- Logo or title -->
        <nav>
            <ul>
                <li><a href="dashboard.php">HOME</a></li> <!-- Link to the dashboard page -->
                <li><a href="#">MY ACCOUNT</a></li> <!-- Link to the student's profile page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> <!-- Link to log out -->
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="upload-file">
        <h2><?php echo htmlspecialchars($activity_name); ?></h2> <!-- Display the activity name securely -->

        <?php if ($passed > 0): ?>
            <p class="submitted-message">You have already submitted this activity.</p> <!-- Inform the student of submission -->

            <?php if (!empty($activity['remarks'])): ?>
                <p class="remarks">Remarks: <?php echo htmlspecialchars($activity['remarks']); ?></p> <!-- Display remarks if available -->
            <?php else: ?>
                <p class="remarks">Remarks: Still not graded</p> <!-- Default message if no remarks -->
            <?php endif; ?>

        <?php else: ?>
            <form action="../php/upload_file.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>"> <!-- Hidden inputs to pass data -->
                <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($student_email); ?>">
                <input type="hidden" name="timepass" value="<?php echo htmlspecialchars($timepass); ?>">
                <input type="hidden" name="activity_name" value="<?php echo htmlspecialchars($activity_name); ?>">
                <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>">

                <label for="student_file">Upload your file:</label>
                <input type="file" id="student_file" name="student_file" required> <!-- File input for uploading -->
                <button type="submit" class="upload-btn">Upload File</button> <!-- Submit button -->
            </form>
        <?php endif; ?>
    </div>
</main>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> <!-- Footer content -->
    </div>
</footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
