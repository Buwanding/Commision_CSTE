<?php

// Retrieve the 'activity_name' and 'subject_name' from the request, defaulting to 'Unknown Activity' and 'Unknown Subject' if not set.
$activity_name = isset($_REQUEST['activity_name']) ? $_REQUEST['activity_name'] : 'Unknown Activity';
$subject_name = isset($_REQUEST['subject_name']) ? $_REQUEST['subject_name'] : 'Unknown Subject';

session_start(); // Start the session to maintain state and access session variables.
require '../php/db.php'; // Include the database connection script to interact with the database.

// Ensure 'activity_id' is provided in the request, or terminate the script with an error message.
if (isset($_REQUEST['activity_id'])) {
    $activity_id = $_REQUEST['activity_id']; // Store the 'activity_id' from the request.
} else {
    die("Activity ID is missing."); // Display an error message and terminate the script if 'activity_id' is not provided.
}

// Prepare an SQL query to fetch activity details (student_email and remarks) based on the provided 'activity_id'.
$activity_query = "SELECT student_email, remarks FROM activity_details WHERE id = ?";
$activity_stmt = $conn->prepare($activity_query); // Prepare the SQL statement to prevent SQL injection.
$activity_stmt->bind_param("i", $activity_id); // Bind the 'activity_id' as an integer parameter to the SQL query.
$activity_stmt->execute(); // Execute the prepared statement.
$activity_result = $activity_stmt->get_result(); // Retrieve the result set from the executed statement.
$activity = $activity_result->fetch_assoc(); // Fetch the activity details as an associative array.
$activity_stmt->close(); // Close the prepared statement to free up resources.

$student_email = $_SESSION['username']; // Store the logged-in student's email from the session.
$timepass = date('Y-m-d'); // Store the current date in the 'timepass' variable.

// Prepare an SQL query to check if the student has already submitted this activity.
$passed_query = "SELECT COUNT(*) AS count FROM activity_details WHERE activity_id = ? AND student_email = ?";
$passed_stmt = $conn->prepare($passed_query); // Prepare the SQL statement to prevent SQL injection.
$passed_stmt->bind_param("is", $activity_id, $student_email); // Bind the 'activity_id' and 'student_email' parameters to the SQL query.
$passed_stmt->execute(); // Execute the prepared statement.
$passed_result = $passed_stmt->get_result(); // Retrieve the result set from the executed statement.
$passed = $passed_result->fetch_assoc()['count']; // Fetch the count of submissions (0 or 1) as an associative array and retrieve the 'count' value.
$passed_stmt->close(); // Close the prepared statement to free up resources.



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Make the webpage responsive to different screen sizes -->
    <title>Upload File</title> <!-- Set the title of the webpage -->
    <link rel="stylesheet" href="./student-css/student-page.css"> <!-- Link to the external CSS file for styling the page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the Font Awesome library for using icons -->
</head>

<body>
<header>
    <div class="header-container">
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> <!-- Display the logo or title of the system -->
        <nav>
            <ul>
                <li><a href="dashboard.php">HOME</a></li> <!-- Link to the dashboard page -->
                <li><a href="#">MY ACCOUNT</a></li> <!-- Link to the student's profile page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> <!-- Link to log out and return to the main page -->
            </ul>
        </nav>
    </div>
</header>

<!-- Provide spacing before the main content -->
<br><br><br><br><br><br><br><br><br><br>

<main>
    <div class="upload-file">
        <h2><?php echo htmlspecialchars($activity_name); ?></h2> <!-- Display the activity name dynamically and securely -->
        
        <!-- Check if the student has already submitted the activity -->
        <?php if ($passed > 0): ?>
            <p class="submitted-message">You have already submitted this activity.</p> <!-- Inform the student that they have already submitted the activity -->
            
            <!-- Display remarks if they exist, otherwise show a default message -->
            <?php if (!empty($activity['remarks'])): ?>
                <p class="remarks">Remarks: <?php echo htmlspecialchars($activity['remarks']); ?></p>
            <?php else: ?>
                <p class="remarks">Remarks: Still not graded</p>
            <?php endif; ?>
        
        <?php else: ?>
            <!-- Display the file upload form if the student has not submitted the activity yet -->
            <form action="../php/upload_file.php" method="post" enctype="multipart/form-data">
                <!-- Hidden input fields to pass necessary data with the form -->
                <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>">
                <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($student_email); ?>">
                <input type="hidden" name="timepass" value="<?php echo htmlspecialchars($timepass); ?>">
                <input type="hidden" name="activity_name" value="<?php echo htmlspecialchars($activity_name); ?>">
                <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>">
                
                <label for="student_file">Upload your file:</label> <!-- Label for the file input -->
                <input type="file" id="student_file" name="student_file" required> <!-- File input field for uploading the student's file -->
                <button type="submit" class="upload-btn">Upload File</button> <!-- Submit button to upload the file -->
            </form>
        <?php endif; ?>
    </div>
</main>

<!-- Provide additional spacing before the footer -->
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> <!-- Footer content -->
    </div>
</footer>
</body>
</html>

