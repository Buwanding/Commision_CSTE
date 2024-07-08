<?php
// Start session to use session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require '../php/db.php';

// Fetch subjects from the database
// $sql = "SELECT subject_name, subject_color FROM subjects WHERE username = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("s", $_SESSION['username']);
// $stmt->execute();
// $result = $stmt->get_result();

// $subjects = [];
// while ($row = $result->fetch_assoc()) {
//     $subjects[] = $row;
// }

// $stmt->close();
// $conn->close();
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
            <a href="#">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Subjects Enrolled</h2>
        <div class="subjects-container">
            <?php foreach ($subjects as $subject): ?>
                <div class="subject-card" style="background-color: <?= $subject['subject_color'] ?>;">
                    <p><?= $subject['subject_name'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>