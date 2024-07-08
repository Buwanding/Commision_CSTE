<?php
// Start session to use session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require '../php/db.php';

// Get the subject name from the query parameter
if (!isset($_GET['subject'])) {
    header("Location: dashboard.php");
    exit();
}

$subject_name = $_GET['subject'];

// Fetch subject details from the database
$sql = "SELECT subject_name, subject_color FROM subjects WHERE username = ? AND subject_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_SESSION['username'], $subject_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Subject not found
    header("Location: dashboard.php");
    exit();
}

$subject = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subject['subject_name']) ?></title>
    <link rel="stylesheet" href="./teacher-styles/subject-style.css">
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($subject['subject_name']) ?></h1>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="../index.html">Logout</a>
        </nav>
    </header>
    <main>
        <div class="subject-details" style="background-color: <?= htmlspecialchars($subject['subject_color']) ?>;">
            <p>Details for subject: <?= htmlspecialchars($subject['subject_name']) ?></p>
            <!-- You can add more details or features related to the subject here -->
        </div>
    </main>
</body>
</html>
