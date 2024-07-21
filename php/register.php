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

    // Insert user data into users table
    $sql = "INSERT INTO users (first_name, last_name, email, contact_number, role, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $contact_number, $role, $password);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        if ($role === 'student') {
            $parent_name = $_POST['parent_name'];
            $parent_contact_number = $_POST['parent_contact_number'];

            // Insert parent data into parents table
            $sql_parent = "INSERT INTO parents (student_id, parents_name, contact_number) VALUES (?, ?, ?)";
            $stmt_parent = $conn->prepare($sql_parent);
            $stmt_parent->bind_param("iss", $user_id, $parent_name, $parent_contact_number);

            if ($stmt_parent->execute()) {
                echo "Record added successfully";
                header("Location: ../index.html");
            } else {
                echo "Error: " . $stmt_parent->error;
            }
            $stmt_parent->close();
        } else {
            echo "Record added successfully";
            header("Location: ../index.html");
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
