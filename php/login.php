<?php
require 'db.php'; // Include the database connection.

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted via POST.
    $username = $_POST['username']; // Get the username from the form.
    $password = $_POST['password']; // Get the password from the form.

    // Prepare a SQL statement to select the user's hashed password and role from the database.
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $username); // Bind the username to the SQL query.
    $stmt->execute(); // Execute the query.
    $stmt->store_result(); // Store the result to check the number of rows.
    $stmt->bind_result($id, $hashed_password, $role); // Bind the result to variables.

    if ($stmt->num_rows > 0) { // Check if the user exists.
        $stmt->fetch(); // Fetch the result.

        if (password_verify($password, $hashed_password)) { // Verify the provided password against the hashed password.
            session_start(); // Start a session.
            // Store user information in session variables.
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role === "teacher") { // Redirect based on the user's role.
                header("Location: ../teachers-page/dashboard.php");
            } else {
                header("Location: ../student-page/dashboard.php");
            }
            exit(); // Stop script execution.
        } else {
            echo '<script>
                    alert("Wrong Credentials");
                    window.location.href = "../index.html";
                  </script>';
            exit(); // Stop script execution.
        }
    } else {
        echo '<script>
                alert("Wrong Credentials");
                window.location.href = "../index.html";
              </script>';
        exit(); // Stop script execution.
    }

    $stmt->close(); // Close the statement.
    $conn->close(); // Close the database connection.
}
?>
