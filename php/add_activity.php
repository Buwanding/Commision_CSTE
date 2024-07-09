<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $activity_name = $_POST['activity_name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO activities (subject_id, activity_name, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $subject_id, $activity_name, $description);

    if ($stmt->execute()) {
        echo "Activity added successfully!";
    } else {
        echo "Error adding activity: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: subject.php?subject=" . urlencode($_POST['subject_name']));
    exit();
}
?>
