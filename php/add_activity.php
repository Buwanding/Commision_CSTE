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
    $deadline = $_POST["deadline"];
     $parents = isset($_POST['parents']) ? $_POST['parents'] : [];

    $sql = "INSERT INTO activities (subject_id, activity_name, description, deadline) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $subject_id, $activity_name, $description, $deadline);

    if ($stmt->execute()) {
        echo "Activity added successfully!";
    } else {
        echo "Error adding activity: " . $stmt->error;
    }

    $stmt->close();
    // $conn->close();
    header("Location: ../php/sendmessage.php?parents=" . urlencode(json_encode($parents)) . "&activity_name=" . urlencode($activity_name));
    exit();
}
?>
