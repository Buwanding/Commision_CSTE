<?php 
$subject_id = $_REQUEST["subject_id"]; 
$subject_name = isset($_REQUEST["subject"]) ? htmlspecialchars($_REQUEST["subject"]) : '';
$subject_des = isset($_REQUEST["subject_des"]) ? htmlspecialchars($_REQUEST["subject_des"]) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($_REQUEST["subject"]); ?></title>
    <link rel="stylesheet" href="./student-css/student-page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<header>
        <div class="header-container">
            <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label>
            <nav>
                <ul>
                    <li><a href="dashboard.php">HOME</a></li>
                    <li><a href="teacher-profile.php">MY ACCOUNT</a></li>
                    <li><a href="../index.html" class="logout">LOGOUT</a></li>
                </ul>
            </nav>
        </div>
    </header>

<br><br><br><br>

    <main>

        <div class="subject-details">
          
            <center> <h1><?php echo htmlspecialchars($_REQUEST["subject"]); ?></h1> </center> 
            <center> <h4><?php echo htmlspecialchars($_REQUEST["subject_des"]); ?> </h4> </center> 

            <br>

            <hr>

            <br>

            <h2> Activities: </h2> 

            <ul>
                <?php
                // Fetch activities for the subject
                require '../php/db.php'; // Ensure database connection is included
                $activity_query = "SELECT * FROM activities WHERE subject_id = ?";
                $activity_stmt = $conn->prepare($activity_query);
                $activity_stmt->bind_param("i", $subject_id);
                $activity_stmt->execute();
                $activity_result = $activity_stmt->get_result();
                
                while ($activity = $activity_result->fetch_assoc()) {
         
                    echo "<li><a href='student_activity.php?activity_id=" . htmlspecialchars($activity['id']) . "&activity_name=" . htmlspecialchars($activity['activity_name']) . "&subject_name=" . htmlspecialchars($subject_name) . "'>" . htmlspecialchars($activity['activity_name']) . "</a>: " . htmlspecialchars($activity['description']) . "</li>";
                }
                
                $activity_stmt->close();
                ?>
            </ul>
        </div>
        
    </main>

    <br><br><br><br><br><br><br><br><br>
    
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p>
        </div>
    </footer>


</body>
</html>
