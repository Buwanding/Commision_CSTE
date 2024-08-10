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
    $deadline = $_POST["deadline"];


    $_SESSION['subject_id'] = $subject_id;
    $_SESSION['activity_name'] = $activity_name;
    $_SESSION['deadline'] = $deadline;

    // $conn->close();
    require "sendmessage.php";
    exit();}
?>
