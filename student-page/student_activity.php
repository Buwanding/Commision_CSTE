<?php
// Ensure database connection is included
require '../php/db.php';

$activity_id = $_REQUEST['activity_id'];

// Fetch activity details based on activity_id
$activity_query = "SELECT subject_id, student_email FROM activity_details WHERE id = ?";
$activity_stmt = $conn->prepare($activity_query);
$activity_stmt->bind_param("i", $activity_id);
$activity_stmt->execute();
$activity_result = $activity_stmt->get_result();
$activity = $activity_result->fetch_assoc();
$activity_stmt->close();

$subject_id = $activity['subject_id'];
$student_email = $activity['student_email'];
$timepass = date('Y-m-d'); // Current date
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link rel="stylesheet" href="./student-styles/subject-style.css">
</head>
<body>
    <header>
        <h1>Upload Your File</h1>
        <nav>
            <a href="subject_details.php?subject_id=<?php echo htmlspecialchars($subject_id); ?>">Back to Subject</a>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="../index.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="upload-file">
            <h2>Upload File</h2>
            <form action="../php/upload_file.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>">
                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>">
                <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($student_email); ?>">
                <input type="hidden" name="timepass" value="<?php echo htmlspecialchars($timepass); ?>">
                <label for="student_file">Upload your file:</label>
                <input type="file" id="student_file" name="student_file" required>
                <button type="submit">Upload File</button>
            </form>
        </div>
    </main>
</body>
</html>
