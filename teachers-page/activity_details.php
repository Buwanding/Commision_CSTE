<?php
// Ensure database connection is included
require '../php/db.php';

$activity_id = $_REQUEST['activity_id'];

// Fetch activity details based on activity_id
$activity_query = "SELECT * FROM activity_details WHERE id = ?";
$activity_stmt = $conn->prepare($activity_query);
$activity_stmt->bind_param("i", $activity_id);
$activity_stmt->execute();
$activity_result = $activity_stmt->get_result();
$activity = $activity_result->fetch_assoc();
$activity_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($activity['details']); ?></title>
    <link rel="stylesheet" href="./teacher-styles/subject-style.css">
</head>
<body>
    <header>
        <h1>Activity Details</h1>
        <nav>
            <a href="subject_details.php?subject_id=<?php echo htmlspecialchars($activity['subject_id']); ?>">Back to Subject</a>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="../index.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="activity-details">
            <h2>Details</h2>
            <p><?php echo htmlspecialchars($activity['details']); ?></p>
            <h2>Deadline</h2>
            <p><?php echo htmlspecialchars($activity['deadline']); ?></p>
            <h2>Remarks</h2>
            <p><?php echo htmlspecialchars($activity['remarks']); ?></p>
            <h2>Student Email</h2>
            <p><?php echo htmlspecialchars($activity['student_email']); ?></p>
            <?php if (!empty($activity['student_file'])): ?>
                <h2>Student File</h2>
                <p><a href="../php/download.php?file_id=<?php echo $activity['id']; ?>">Download File</a></p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

