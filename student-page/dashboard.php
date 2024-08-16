session_start(); // Start the session to maintain state and access session variables.

// Check if the user is logged in by verifying if 'username' is set in the session.
if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to the login page if the user is not logged in.
    exit(); // Stop further execution of the script.
}

require '../php/db.php'; // Include the database connection script to interact with the database.

// Prepare an SQL query to fetch subjects associated with the logged-in student.
$sql = "SELECT subjects.id, subject_name, subject_color, subject_description 
        FROM subjects 
        INNER JOIN student_subjects ON subjects.id = student_subjects.subject_id 
        WHERE student_subjects.student_email = ?";

// Prepare the SQL statement for execution, preventing SQL injection by using placeholders.
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']); // Bind the student's email (from the session) to the SQL statement.
$stmt->execute(); // Execute the prepared statement.
$result = $stmt->get_result(); // Get the result set from the executed statement.

// Initialize an empty array to store the subjects.
$subjects = [];
while ($row = $result->fetch_assoc()) { // Fetch each row of the result as an associative array.
    $subjects[] = $row; // Add each subject's data to the $subjects array.
}

$stmt->close(); // Close the prepared statement to free up resources.
$conn->close(); // Close the database connection to free up resources.

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Make the webpage responsive to different screen sizes -->
    <title>Dashboard</title> <!-- Set the title of the webpage -->
    <link rel="stylesheet" href="../css/dashboard-style.css"> <!-- Link to the external CSS file for styling the dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to the Font Awesome library for using icons -->
</head>

<body>
<header>
    <div class="header-container">
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> <!-- Display the logo or title of the system -->
        <nav>
            <ul>
                <li><a href="dashboard.php">HOME</a></li> <!-- Link to the dashboard (current page) -->
                <li><a href="student-profile.php">MY ACCOUNT</a></li> <!-- Link to the student's profile page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> <!-- Link to log out and return to the main page -->
            </ul>
        </nav>
    </div>
</header>

<!-- Provide spacing before the main content -->
<br><br><br><br><br><br><br><br><br><br>

<main>
    <h2>Subjects Enrolled</h2> <!-- Heading for the subjects section -->
    <div class="subjects-container">
        <!-- Loop through the $subjects array to display each subject as a card -->
        <?php foreach ($subjects as $subject): ?>
            <!-- Each subject is displayed in a card with a background color and a link to its details -->
            <div class="subject-card" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
                <a href="student_subjectpage.php?subject=<?= urlencode($subject['subject_name']) ?>&subject_id=<?= $subject['id'] ?>&subject_des=<?= $subject['subject_description'] ?>" style="text-decoration: none; color: inherit;">
                    <p><?= htmlspecialchars($subject['subject_name']) ?></p> <!-- Display the subject name -->
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <br>
    <br>

</main>

<!-- Provide additional spacing before the footer -->
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> <!-- Footer content -->
    </div>
</footer>

</body>
</html>
