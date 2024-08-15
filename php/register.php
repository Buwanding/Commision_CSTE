<?php
require 'db.php'; // Include the database connection.
ini_set('display_errors', 1); // Enable error reporting.
ini_set('display_startup_errors', 1); // Enable startup errors.
error_reporting(E_ALL); // Report all errors.

// Check if the form is submitted via POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data.
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password.

    // Prepare an SQL statement to insert the user data into the database.
    $sql = "INSERT INTO users (first_name, last_name, email, contact_number, role, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); // Prepare the SQL query.
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $contact_number, $role, $password); // Bind the parameters.

    if ($stmt->execute()) { // Execute the query and check if it was successful.
        $user_id = $stmt->insert_id; // Get the ID of the inserted user.

        if ($role === 'student') { // If the user is a student, add parent information.
            $parent_name = $_POST['parent_name'];
            $parent_contact_number = $_POST['parent_contact_number'];

            // Prepare an SQL statement to insert the parent data.
            $sql_parent = "INSERT INTO parents (student_id, parents_name, contact_number) VALUES (?, ?, ?)";
            $stmt_parent = $conn->prepare($sql_parent);
            $stmt_parent->bind_param("iss", $user_id, $parent_name, $parent_contact_number);

            if ($stmt_parent->execute()) { // Execute the query for the parent data.
                echo "Record added successfully";
                header("Location: ../index.html"); // Redirect to the login page.
            } else {
                echo "Error: " . $stmt_parent->error; // Output an error message if something went wrong.
            }
            $stmt_parent->close(); // Close the parent statement.
        } else {
            echo "Record added successfully";
            header("Location: ../index.html"); // Redirect to the login page.
        }
    } else {
        echo "Error: " . $stmt->error; // Output an error message if something went wrong.
    }

    $stmt->close(); // Close the statement.
}

$conn->close(); // Close the database connection.
?>
