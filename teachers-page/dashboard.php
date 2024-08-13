<?php
// Start session to use session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require '../php/db.php';

// Handle new subject submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject_name']) && isset($_POST['subject_color']) && isset($_POST['subject_description'])) {
    $subject_name = $_POST['subject_name'];
    $subject_color = $_POST['subject_color'];
    $subject_description = $_POST['subject_description'];

    $sql = "INSERT INTO subjects (username, subject_name, subject_color, subject_description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_SESSION['username'], $subject_name, $subject_color, $subject_description);
    $stmt->execute();
    $stmt->close();
}


// Fetch subjects from the database
$sql = "SELECT id, subject_name, subject_color, subject_description FROM subjects WHERE username = ?";
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
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label>
        <nav>
            <ul>
                <li><a href="dashboard.php">  HOME </i> </a></li>
                <li><a href="teacher-profile.php"> PROFILE </i> </a></li>
                <li><a href="../index.html" class="logout">Logout</a></li>
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
            <div class="subject-card" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
                <a href="subject_page.php?subject=<?= urlencode($subject['subject_name']) ?>&subject_id=<?= $subject['id'] ?>&subject_des=<?= urlencode($subject['subject_description']) ?>" style="text-decoration: none; color: inherit;">
                    <p><?= htmlspecialchars($subject['subject_name']) ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <br><br>

    <h2>Add New Subject</h2>


    <form action="" method="post" class="add-subject-form">

    <br>

    <form action="" method="post" class="add-subject-form">
            <div class="form-group">
                <label for="subject_name">Subject Name:</label>
                <input type="text" id="subject_name" name="subject_name" placeholder="Enter subject name" required>
            </div>

            <div class="form-group">
                <label for="subject_color">Subject Color:</label>
                <input type="color" id="subject_color" name="subject_color" required>
            </div>

            <div class="form-group">
                <label for="subject_description">Subject Description:</label>
                <textarea id="subject_description" name="subject_description" rows="4" cols="50" placeholder="Enter subject description" required></textarea>
            </div>

            <button type="submit">Add Subject</button>
        <br>

    </form>

    <br><br>

</main>

<br><br>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p>
    </div>
</footer>

</body>
</html>
