<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $student_name = $_POST['student_name'];

    $sql = "INSERT INTO student_subjects (subject_id, student_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $subject_id, $student_name);

    if ($stmt->execute()) {
        echo "Student assigned successfully!";
    } else {
        echo "Error assigning student: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: subject.php?subject=" . urlencode($_POST['subject_name']));
    exit();
}
?>
