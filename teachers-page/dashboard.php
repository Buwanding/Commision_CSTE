<?php
// Start session to use session variables
session_start(); // Initiates a session or resumes the current one, enabling the use of session variables.

// Check if the user is logged in
if (!isset($_SESSION['username'])) { 
    header("Location: index.html"); // Redirects to the login page if the 'username' session variable is not set, meaning the user is not logged in.
    exit(); // Ensures the script stops executing after the redirect.
}

require '../php/db.php'; // Includes the database connection script to establish a connection to the database.

// Handle new subject submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject_name']) && isset($_POST['subject_color']) && isset($_POST['subject_description'])) {
    // Checks if the request method is POST and that the required form fields are set, indicating a form submission.

    $subject_name = $_POST['subject_name']; // Retrieves the 'subject_name' from the POST data.
    $subject_color = $_POST['subject_color']; // Retrieves the 'subject_color' from the POST data.
    $subject_description = $_POST['subject_description']; // Retrieves the 'subject_description' from the POST data.

    // SQL query to insert the new subject into the 'subjects' table, using placeholders for security.
    $sql = "INSERT INTO subjects (username, subject_name, subject_color, subject_description) VALUES (?, ?, ?, ?)";
    
    // Prepares the SQL statement to prevent SQL injection attacks.
    $stmt = $conn->prepare($sql);
    
    // Binds the session username and form inputs to the prepared SQL statement.
    $stmt->bind_param("ssss", $_SESSION['username'], $subject_name, $subject_color, $subject_description);
    
    // Executes the prepared statement, inserting the data into the database.
    $stmt->execute();
    
    // Closes the prepared statement to free up resources.
    $stmt->close();
}

// Fetch subjects from the database
// SQL query to select the subject details for the logged-in user from the 'subjects' table.
$sql = "SELECT id, subject_name, subject_color, subject_description FROM subjects WHERE username = ?";
$stmt = $conn->prepare($sql); // Prepares the SQL statement.
$stmt->bind_param("s", $_SESSION['username']); // Binds the 'username' session variable to the prepared SQL statement.
$stmt->execute(); // Executes the prepared statement.
$result = $stmt->get_result(); // Gets the result set from the executed statement.

$subjects = []; // Initializes an empty array to store the fetched subjects.
while ($row = $result->fetch_assoc()) { 
    // Loops through the result set and fetches each row as an associative array.
    $subjects[] = $row; // Adds each fetched row (subject) to the 'subjects' array.
}

$stmt->close(); // Closes the prepared statement.
$conn->close(); // Closes the database connection.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-style.css"> <!-- Link to the external CSS file for styling the dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to Font Awesome icons -->
</head>

<body>

<header>
    <div class="header-container">
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> <!-- System logo -->
        <nav>
            <ul>
                <li><a href="dashboard.php">  HOME </i> </a></li> <!-- Link to the home (dashboard) page -->
                <li><a href="teacher-profile.php"> MY ACCOUNT </i> </a></li> <!-- Link to the teacher's profile page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> <!-- Link to logout (redirects to login page) -->
            </ul>
        </nav>
    </div>
</header>

<br>
<br>

<main>
    <h2>Subjects Handled</h2>
    <div class="subjects-container">
        <?php foreach ($subjects as $subject): ?>
            <!-- Loop through each subject in the $subjects array -->
            <div class="subject-card" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
                <!-- Display each subject as a card, with the background color set to the subject's color -->
                <a href="subject_page.php?subject=<?= urlencode($subject['subject_name']) ?>&subject_id=<?= $subject['id'] ?>&subject_des=<?= urlencode($subject['subject_description']) ?>" style="text-decoration: none; color: inherit;">
                    <!-- Link to the subject's detailed page, passing subject name, ID, and description as query parameters -->
                    <p><?= htmlspecialchars($subject['subject_name']) ?></p> <!-- Display the subject name -->
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <br><br>

    <h2>Add New Subject</h2>

    <form action="" method="post" class="add-subject-form">
        <br>
        <div class="form-group">
            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" placeholder="Enter subject name" required>
            <!-- Input field for entering the subject name, required for form submission -->
        </div>

        <div class="form-group">
            <label for="subject_color">Subject Color:</label>
            <input type="color" id="subject_color" name="subject_color" required>
            <!-- Input field for selecting the subject color, required for form submission -->
        </div>

        <div class="form-group">
            <label for="subject_description">Subject Description:</label>
            <textarea id="subject_description" name="subject_description" rows="4" cols="50" placeholder="Enter subject description" required></textarea>
            <!-- Textarea for entering the subject description, required for form submission -->
        </div>

        <button type="submit">Add Subject</button> <!-- Button to submit the form -->
        <br>
    </form>

    <br><br>
</main>

<br><br>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> <!-- Footer with copyright notice -->
    </div>
</footer>

</body>
</html>
