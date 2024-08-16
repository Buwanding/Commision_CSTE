<?php
// Retrieve the subject ID, name, and description from the request parameters
$subject_id = isset($_REQUEST["subject_id"]) ? $_REQUEST["subject_id"] : '';
$subject_name = isset($_REQUEST["subject"]) ? htmlspecialchars($_REQUEST["subject"]) : '';
$subject_des = isset($_REQUEST["subject_des"]) ? htmlspecialchars($_REQUEST["subject_des"]) : '';

// Ensure subject_id and subject_name are set and valid
if (empty($subject_id) || empty($subject_name)) {
    // Handle the error or redirect to a different page if either is missing
    echo "Subject not found!";
    exit(); // Stop script execution if subject is not found
}

require '../php/db.php'; // Include the database connection

// Fetch students and their parents related to this subject
$stud_query = "SELECT * FROM student_subjects WHERE subject_id = ?";
$stud_stmt = $conn->prepare($stud_query); // Prepare the SQL statement to prevent SQL injection
$stud_stmt->bind_param("i", $subject_id); // Bind the subject ID to the prepared statement
$stud_stmt->execute(); // Execute the statement
$stud_result = $stud_stmt->get_result(); // Get the result of the query

$students = []; // Initialize an array to store student and parent information
while ($stud = $stud_result->fetch_assoc()) { 
    // Loop through each student associated with the subject
    $parent_query = "SELECT parents.parents_name, parents.contact_number 
                     FROM parents 
                     INNER JOIN users ON parents.student_id = users.id 
                     WHERE users.email = ?";
    $parent_stmt = $conn->prepare($parent_query); // Prepare the query to fetch parent details
    $parent_stmt->bind_param("s", $stud['student_email']); // Bind the student's email to the query
    $parent_stmt->execute(); // Execute the query
    $parent_result = $parent_stmt->get_result(); // Get the result of the query
    
    $parents = []; // Initialize an array to store parent information for each student
    while ($parent = $parent_result->fetch_assoc()) {
        // Loop through each parent associated with the student
        $parents[] = $parent; // Add parent details to the array
    }
    $students[] = [
        'student' => $stud, // Store student details
        'parents' => $parents // Store associated parent details
    ];
    $parent_stmt->close(); // Close the statement for fetching parent details
}

$stud_stmt->close(); // Close the statement for fetching student details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($subject_name); ?></title> <!-- Set the page title to the subject name -->
    <link rel="stylesheet" href="./teacher-styles/subject-style.css"> <!-- Link to the CSS file for styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to Font Awesome icons -->
</head>
<body>

<header>
    <div class="header-container">
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> <!-- System logo -->
        <nav>
            <ul>
                <li><a href="dashboard.php">  HOME </i> </a></li> <!-- Link to the dashboard -->
                <li><a href="teacher-profile.php"> MY ACCOUNT </i> </a></li> <!-- Link to the teacher's profile page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> <!-- Link to logout -->
            </ul>
        </nav>
    </div>
</header>

<br><br><br>

<main>
    <div class="subject-details"> 
        <center><h1><?php echo htmlspecialchars($subject_name); ?></h1></center> <!-- Display the subject name -->
        <center><h4><?php echo htmlspecialchars($subject_des); ?></h4></center> <!-- Display the subject description -->

        <br>
        
        <hr width="800px"> <!-- Horizontal rule for visual separation -->
        
        <br>

        <!-- Form to add activities to the subject -->
        <div class="form-container">
        <h2>Add Activity</h2>
            <br>
        <form action="../php/add_activity.php" method="post"> <!-- Form submission to add a new activity -->
                <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>"> <!-- Hidden field to pass the subject ID -->
                <label for="activity_name">Activity Name:</label>
                <input type="text" id="activity_name" name="activity_name" required> <!-- Input field for the activity name -->
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea> <!-- Textarea for the activity description -->
                <label for="deadline">Deadline:</label>
                <input type="date" id="deadline" name="deadline" required> <!-- Date picker for the activity deadline -->
                <button type="submit">Add Activity</button> <!-- Submit button -->
            </form>
        </div>

        <br><br>

        <!-- Form to assign students to the subject -->
        <div class="form-container">
            <h2>Assign Student</h2>
            <br>
            <form action="../php/assign_student.php" method="post"> <!-- Form submission to assign a student -->
                <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>"> <!-- Hidden field to pass the subject ID -->
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required> <!-- Input field for the student's name -->
                <button type="submit">Assign Student</button> <!-- Submit button -->
            </form>
        </div>

        <br><br><br>

        <!-- Display the list of activities for the subject -->
        <div class="list-container">
        <h2>Activities</h2>
        <ul>
            <?php
            // Fetch activities for the subject
            $activity_query = "SELECT * FROM activities WHERE subject_id = ?";
            $activity_stmt = $conn->prepare($activity_query); // Prepare the SQL statement
            $activity_stmt->bind_param("i", $subject_id); // Bind the subject ID to the query
            $activity_stmt->execute(); // Execute the query
            $activity_result = $activity_stmt->get_result(); // Get the result set
            
            while ($activity = $activity_result->fetch_assoc()) { 
                // Loop through each activity associated with the subject
                echo "<li class='activity-item'>" .

                        "<span class='activity-info'>" .
                            "<a href='activity_details.php?activity_id=" . htmlspecialchars($activity['id'])  . "&subject_name=" .
                            htmlspecialchars($subject_name) . "&activity_name=". htmlspecialchars($activity['activity_name']) . "'>" . htmlspecialchars($activity['activity_name']).
                            "</a>: " . htmlspecialchars($activity['description']) . // Display the activity details with a link to its page
                        "</span>" .

                        "<form action='../php/update.php' method='post'>" .
                            "<input type='hidden' name='subject_id' value='" . htmlspecialchars($subject_id) . "'>" . 
                            "<input type='hidden' name='activity_name' value='" . htmlspecialchars($activity['activity_name']) . "'>" .
                            "<input type='hidden' name='deadline' value='" . htmlspecialchars($activity['deadline']) . "'>" .
                            "<button type='submit' class='update-button'>Update</button>" . // Form to update the activity
                        "</form>" .

                    "</li>";
            }
            
            $activity_stmt->close(); // Close the statement for fetching activities
            ?>
        </ul>
</div>

        <br><br>

        <!-- Display the list of students assigned to the subject -->
        <div class="list-container">
            <h2>Assigned Students</h2>
            <ul>
                <?php
                // Fetch students assigned to the subject
                $student_query = "SELECT * FROM student_subjects WHERE subject_id = ?";
                $student_stmt = $conn->prepare($student_query); // Prepare the SQL statement
                $student_stmt->bind_param("i", $subject_id); // Bind the subject ID to the query
                $student_stmt->execute(); // Execute the query
                $student_result = $student_stmt->get_result(); // Get the result set
                
                while ($student = $student_result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($student['student_email']) . "</li>"; // Display each student's email
                }
                
                $student_stmt->close(); // Close the statement for fetching students
                ?>
            </ul>
        </div>

        <br><br>

        <!-- Display the list of parents associated with the students -->
        <div class="list-container">
            <h2>List Parents</h2>
            <?php foreach ($students as $student_data): ?>
                <div class="student-card">
                    <?php foreach ($student_data['parents'] as $parent): ?>
                        <ul>
                            <li><?= htmlspecialchars($parent['parents_name']) ?></li> <!-- Display parent's name -->
                            <li><?= htmlspecialchars($parent['contact_number']) ?></li> <!-- Display parent's contact number -->
                        </ul>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<br><br><br>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> <!-- Footer content -->
    </div>
</footer>

</body>
</html>
