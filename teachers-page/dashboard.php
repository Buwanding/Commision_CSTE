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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject_name']) && isset($_POST['subject_color'])) {
    $subject_name = $_POST['subject_name'];
    $subject_color = $_POST['subject_color'];

    $sql = "INSERT INTO subjects (username, subject_name, subject_color) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $_SESSION['username'], $subject_name, $subject_color);
    $stmt->execute();
    $stmt->close();
}

// Fetch subjects from the database
$sql = "SELECT id, subject_name, subject_color FROM subjects WHERE username = ?";
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
        <div class="system-name">
            <h1>SAMS</h1>
        </div>
        <div class="icons">
            <div class="notification-icon">
                <a href="#">
                    <i class="fa fa-bell" style="font-size:24px"></i>
                </a>
            </div>
            <div class="profile-icon">
                <a href="#">
                    <i class="fa fa-user" style="font-size:24px"></i>
                </a>
            </div>
            <div class="logout">
                <a href="../index.html">LOGOUT</a>
            </div>
        </div>
    </div>
</header>

<br>
<hr width="100%">
<br>

<main>
    <h2>Subjects Handled</h2>
    <div class="subjects-container">
        <?php foreach ($subjects as $subject): ?>
            <div class="subject-card" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
                <a href="subject_page.php?subject=<?= urlencode($subject['subject_name']) ?>&subject_id=<?= $subject['id'] ?>" style="text-decoration: none; color: inherit;">
                    <p><?= htmlspecialchars($subject['subject_name']) ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <br>
    <br>
    <br>

    <h2>ADD NEW SUBJECT</h2>
    <form action="" method="post" class="add-subject-form">
        <label for="subject_name">Subject Name:</label>
        <input type="text" id="subject_name" name="subject_name" required>
        
        <label for="subject_color">Subject Color:</label>
        <input type="color" id="subject_color" name="subject_color" required>
        
        <button type="submit">Add Subject</button>
    </form>
</main>
</body>
</html>
