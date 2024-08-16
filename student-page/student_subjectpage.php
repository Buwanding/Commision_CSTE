<?php 

// Retrieve the 'subject_id' from the request, which is expected to be provided in the URL or form data.
$subject_id = $_REQUEST["subject_id"]; 

// Check if 'subject' and 'subject_des' are provided in the request.
// If they are, assign them to variables after sanitizing with htmlspecialchars to prevent XSS attacks.
// If they are not provided, default to an empty string.
$subject_name = isset($_REQUEST["subject"]) ? htmlspecialchars($_REQUEST["subject"]) : ''; 
$subject_des = isset($_REQUEST["subject_des"]) ? htmlspecialchars($_REQUEST["subject_des"]) : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding for the document to UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ensure the webpage is responsive to different screen sizes -->
    
    <!-- Dynamically set the title of the page using the subject name, ensuring it is properly escaped to avoid XSS attacks -->
    <title><?php echo htmlspecialchars($_REQUEST["subject"]); ?></title>
    
    <!-- Link to the external CSS file for the page's styling -->
    <link rel="stylesheet" href="./student-css/student-page.css">
    
    <!-- Link to the Font Awesome library for using icons in the HTML -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<header>
    <div class="header-container">
        <!-- Display the title of the system in the header -->
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> 
        <nav>
            <ul>
                <!-- Navigation link to the dashboard page -->
                <li><a href="dashboard.php">HOME</a></li> 
                <!-- Navigation link to the teacher's profile page -->
                <li><a href="teacher-profile.php">MY ACCOUNT</a></li> 
                <!-- Navigation link to log out, redirecting to the main page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> 
            </ul>
        </nav>
    </div>
</header>

<!-- Add spacing before the main content for layout purposes -->
<br><br><br><br>

<main>
    <div class="subject-details">
        <!-- Display the subject name in a large heading, centered on the page -->
        <center>
            <h1><?php echo htmlspecialchars($_REQUEST["subject"]); ?></h1>
        </center>
        <!-- Display the subject description in a smaller heading, centered below the subject name -->
        <center>
            <h4><?php echo htmlspecialchars($_REQUEST["subject_des"]); ?></h4>
        </center>

        <!-- Additional spacing and a horizontal rule for visual separation -->
        <br>
        <hr>
        <br>

        <!-- Header for the list of activities associated with the subject -->
        <h2>Activities:</h2> 

        <ul>
            <?php
            // Include the database connection script to establish a connection to the database.
            require '../php/db.php'; 
            
            // Prepare an SQL query to retrieve activities related to the subject by its ID.
            $activity_query = "SELECT * FROM activities WHERE subject_id = ?";
            
            // Prepare the SQL statement to prevent SQL injection.
            $activity_stmt = $conn->prepare($activity_query); 
            
            // Bind the 'subject_id' as an integer parameter to the SQL query.
            $activity_stmt->bind_param("i", $subject_id); 
            
            // Execute the prepared statement.
            $activity_stmt->execute(); 
            
            // Retrieve the result set from the executed statement.
            $activity_result = $activity_stmt->get_result(); 
            
            // Loop through each activity retrieved from the database.
            while ($activity = $activity_result->fetch_assoc()) {
                // Create a list item with a link to the 'student_activity.php' page, passing activity details in the URL query string.
                // Each activity name is displayed as a clickable link, followed by its description.
                echo "<li><a href='student_activity.php?activity_id=" . htmlspecialchars($activity['id']) . "&activity_name=" . htmlspecialchars($activity['activity_name']) . "&subject_name=" . htmlspecialchars($subject_name) . "'>" . htmlspecialchars($activity['activity_name']) . "</a>: " . htmlspecialchars($activity['description']) . "</li>";
            }
            
            // Close the prepared statement to free up resources.
            $activity_stmt->close(); 
            ?>
        </ul>
    </div>
</main>

<!-- Additional spacing before the footer for layout purposes -->
<br><br><br><br><br><br><br><br><br>

<footer>
    <div class="footer-container">
        <!-- Footer content, displaying the copyright notice -->
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> 
    </div>
</footer>

</body>
</html>

