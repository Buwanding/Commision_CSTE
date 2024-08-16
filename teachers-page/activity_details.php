<?php
// Ensure database connection is included
$activity_name = isset($_REQUEST['activity_name']) ? $_REQUEST['activity_name'] : 'Unknown Activity'; // Retrieve activity name from the request, default to 'Unknown Activity' if not set
$subject_name = isset($_REQUEST['subject_name']) ? $_REQUEST['subject_name'] : 'Unknown Subject'; // Retrieve subject name from the request, default to 'Unknown Subject' if not set
require '../php/db.php'; // Include the database connection file

// Fetch activity details
$activity_id = $_GET['activity_id'] ?? null; // Get the activity ID from the URL query parameters, set to null if not provided

if ($activity_id) {
    // Prepare the SQL query to fetch activity details
    $activity_query = "SELECT ad.id AS ad_id, ad.student_email, ad.remarks, ad.timepass, ad.student_file_path, a.description, a.deadline 
                       FROM activity_details ad 
                       JOIN activities a ON ad.activity_id = a.id 
                       WHERE ad.activity_id = ?";
    $activity_stmt = $conn->prepare($activity_query); // Prepare the SQL statement
    $activity_stmt->bind_param("i", $activity_id); // Bind the activity ID to the prepared statement
    $activity_stmt->execute(); // Execute the prepared statement
    $activity_result = $activity_stmt->get_result(); // Get the result set from the executed statement
} else {
    echo "Activity ID is missing."; // Display an error message if the activity ID is not provided
    exit(); // Stop further execution of the script
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding of the document to UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Make the page responsive on all devices -->
    <title>Activity Details</title> <!-- Set the title of the document -->
    <link rel="stylesheet" href="./teacher-styles/subject-style.css"> <!-- Link to the external CSS stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Include Font Awesome icons -->
</head>
<body>
    <header>
        <div class="header-container">
            <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> <!-- Logo label -->
            <nav>
                <ul>
                    <li><a href="dashboard.php">HOME</a></li> <!-- Link to the home page -->
                    <li><a href="teacher-profile.php">MY ACCOUNT</a></li> <!-- Link to the teacher's profile -->
                    <li><a href="../index.html" class="logout">LOGOUT</a></li> <!-- Link to logout -->
                </ul>
            </nav>
        </div>
    </header>

    <br><br>

    <main>
        <div class="activity-details">
            <center><h1>ACTIVITY DETAILS</h1></center> <!-- Centered heading for the activity details -->
            <h2><?php echo htmlspecialchars($activity_name); ?></h2> <!-- Display the activity name, ensuring special characters are escaped -->
            <br><br><br><br>
            <table>
                <thead>
                    <tr>
                        <th>Student Email</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Time Submitted</th>
                        <th>Remarks</th>
                        <th>Student File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($activity = $activity_result->fetch_assoc()): ?> <!-- Loop through each activity record -->
                        <tr>
                            <td><?php echo htmlspecialchars($activity['student_email'] ?? 'N/A'); ?></td> <!-- Display student email, default to 'N/A' if not available -->
                            <td><?php echo htmlspecialchars($activity['description'] ?? 'N/A'); ?></td> <!-- Display activity description, default to 'N/A' if not available -->
                            <td><?php echo htmlspecialchars($activity['deadline'] ?? 'N/A'); ?></td> <!-- Display activity deadline, default to 'N/A' if not available -->
                            <td><?php echo htmlspecialchars($activity['timepass'] ?? 'N/A'); ?></td> <!-- Display the time the activity was submitted, default to 'N/A' if not available -->
                            <td>
                                <!-- Form to update remarks -->
                                <form action="../php/update_remarks.php" method="post">
                                    <input type="hidden" name="ad_id" value="<?php echo htmlspecialchars($activity['ad_id']); ?>"> <!-- Hidden input to pass the activity details ID -->
                                    <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($activity['student_email']); ?>"> <!-- Hidden input to pass the student email -->
                                    <input type="text" id="remarks" name="remarks" value="<?php echo htmlspecialchars($activity['remarks'] ?? ''); ?>" required> <!-- Input field to update remarks, pre-filled with existing remarks -->
                                    <input type="hidden" name="activity_name" value="<?php echo htmlspecialchars($activity_name); ?>"> <!-- Hidden input to pass the activity name -->
                                    <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>"> <!-- Hidden input to pass the subject name -->
                                    <button type="submit">Update Remarks</button> <!-- Button to submit the form -->
                                </form>
                            </td>
                            <td>
                                <?php if (!empty($activity['student_file_path'])): ?> <!-- Check if the student has submitted a file -->
                                    <a href="../php/download.php?file_path=<?php echo urlencode($activity['student_file_path']); ?>" class="download-btn"> <!-- Link to download the student's file -->
                                         Download File
                                    </a>
                                <?php else: ?>
                                    N/A <!-- Display 'N/A' if no file was submitted -->
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endwhile; ?> <!-- End of the loop -->
                </tbody>
            </table>
        </div>
    </main>

    <br><br><br><br><br><br><br>

    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> <!-- Footer text -->
        </div>
    </footer>
</body>
</html>

<?php
// Close the statement and connection
$activity_stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>

