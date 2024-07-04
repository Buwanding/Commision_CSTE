<?php
require 'db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password


$sql = "INSERT INTO users (first_name, last_name, email, contact_number, role, password) VALUES ('$first_name', '$last_name', '$email', '$contact_number', '$role', '$password')";
        $res = $conn->query($sql);
        if ($res === TRUE) {
            echo "Record added";
        } else {
            echo "Error: " . $conn->error;
        }
    }
?>
