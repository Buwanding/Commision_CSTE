<?php
session_start();
require '../php/db.php';

$activity_id = $_REQUEST['activity_id'];
//getting the data from previous page
// Fetch activity details based on activity_id
$activity_query = "SELECT student_email, remarks FROM activity_details WHERE id = ?";
$activity_stmt = $conn->prepare($activity_query);
$activity_stmt->bind_param("i", $activity_id);
$activity_stmt->execute();
$activity_result = $activity_stmt->get_result();
$activity = $activity_result->fetch_assoc();
$activity_stmt->close();

$student_email = $_SESSION['username'];
$timepass = date('Y-m-d'); // Current date

// Check if the student already passed the activity
$passed_query = "SELECT COUNT(*) AS count FROM activity_details WHERE activity_id = ? AND student_email = ?";
$passed_stmt = $conn->prepare($passed_query);
$passed_stmt->bind_param("is", $activity_id, $student_email);
$passed_stmt->execute();
$passed_result = $passed_stmt->get_result();
$passed = $passed_result->fetch_assoc()['count'];
$passed_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
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

<br>
<br>

<main>
        <div class="upload-file">
            <h2>Upload File</h2>
            <?php if ($passed > 0): ?>
                <p class="submitted-message">You have already submitted this activity.</p>
                <?php if (!empty($activity['remarks'])): ?>
                    <p class="remarks">Remarks: <?php echo htmlspecialchars($activity['remarks']); ?></p>
                <?php else: ?>
                    <p class="remarks">Remarks: Still not graded</p>
                <?php endif; ?>
            <?php else: ?>
                <form action="../php/upload_file.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="activity_id" value="<?php echo htmlspecialchars($activity_id); ?>">
                    <input type="hidden" name="student_email" value="<?php echo htmlspecialchars($student_email); ?>">
                    <input type="hidden" name="timepass" value="<?php echo htmlspecialchars($timepass); ?>">
                    <label for="student_file">Upload your file:</label>
                    <input type="file" id="student_file" name="student_file" required>
                    <button type="submit" class="upload-btn">Upload File</button>
                </form>
            <?php endif; ?>
        </div>
    </main>

    

    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
