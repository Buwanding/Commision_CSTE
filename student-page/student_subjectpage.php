<?php $subject_id = $_REQUEST["subject_id"]; ?>
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
        <h1><?php echo htmlspecialchars($_REQUEST["subject"]); ?></h1>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="../index.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="subject-details">
          
            <!-- Display activities and students -->
            <h2>Activities</h2>
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
                    echo "<li><a href='activity_details.php?activity_id=" . htmlspecialchars($activity['id']) . "'>" . htmlspecialchars($activity['activity_name']) . "</a>: " . htmlspecialchars($activity['description']) . "</li>";
                }
                
                $activity_stmt->close();
                ?>
            </ul>
        </div>
    </main>
</body>
</html>
