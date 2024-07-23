<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require '../php/db.php';

// Fetch subjects from the database
$sql = "SELECT subjects.id,subject_name, subject_color, subject_description FROM subjects INNER JOIN student_subjects  ON subjects.id = student_subjects.subject_id WHERE student_subjects.student_email = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
        <h2>Subjects Handled</h2>
        <div class="subjects-container">
            <?php foreach ($subjects as $subject): ?>
                <div class="subject-card" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
                    <a href="student_subjectpage.php?subject=<?= urlencode($subject['subject_name']) ?>&subject_id=<?= $subject['id'] ?>&subject_des=<?= $subject['subject_description'] ?>" style="text-decoration: none; color: inherit;">
                        <p><?= htmlspecialchars($subject['subject_name']) ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <br>
        <br>
    </main>

    <br>
    <br>
    <br>

    <footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p>
    </div>
    </footer>

</body>
</html>