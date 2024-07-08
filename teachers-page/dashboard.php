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
$sql = "SELECT subject_name, subject_color FROM subjects WHERE username = ?";
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
</head>
<body>
    <header>
        <h1>SAMS</h1>
        <nav>
            <a href="#">Profile</a>
            <a href="../index.html">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Subjects Handled</h2>
        <div class="subjects-container">
            <?php foreach ($subjects as $subject): ?>
                <div class="subject-card" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
                    <a href="subject_page.php?subject=<?= urlencode($subject['subject_name']) ?>" style="text-decoration: none; color: inherit;">
                        <p><?= htmlspecialchars($subject['subject_name']) ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <h2>Add New Subject</h2>
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
