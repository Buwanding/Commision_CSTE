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
</head>
<body>

<header>
        <div class="header-container">

            <!-- <div class="logo">
                <img>
            </div> -->

            <div class="system-name">
                <h1>Student Activity Management System</h1>
            </div>
            <div class="icons">
                <div class="notification-icon">
                    <a href
                    	<i style="font-size:24px" class="fa">&#xf0f3;</i>
                    </a>
                </div>
                <div class="profile-icon">
                    <a href="#">
						<i style="font-size:24px" class="fa">&#xf007;</i>
                    </a>
                </div>
                <div class="logout">
                    <a href="../index.html">LOGOUT</a>
                </div>
            </div>
        </div>
</header>

    <main>

        <div class="subject-details">
          
            <center> <h1><?php echo htmlspecialchars($_REQUEST["subject"]); ?></h1> </center> 
            <h2><?php echo htmlspecialchars($_REQUEST["subject_des"]); ?> </h2>

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
                    echo "<li><a href='student_activity.php?activity_id=" . htmlspecialchars($activity['id']) . "'>" . htmlspecialchars($activity['activity_name']) . "</a>: " . htmlspecialchars($activity['description']) . "</li>";
                }
                
                $activity_stmt->close();
                ?>
            </ul>
        </div>
        
    </main>
</body>
</html>
