<?php
session_start(); 
// Start the session to manage user sessions and track the logged-in user's information.

require '../php/db.php'; 
// Include the database connection file to establish a connection to the database.

if (!isset($_SESSION['username'])) { 
    // Check if the 'username' session variable is set (indicating the user is logged in).
    header("Location: ../index.html"); 
    // If the user is not logged in, redirect them to the login page.
    exit(); 
    // Terminate the script to ensure no further code is executed.
}

// Handle password update when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Check if the form was submitted via POST request.
    $current_password = $_POST["current_password"]; 
    $new_password = $_POST["new_password"]; 
    $confirm_password = $_POST["confirm_password"]; 
    // Retrieve the form inputs: current password, new password, and confirmation of the new password.

    // Fetch the user's current password hash from the database
    $email = $_SESSION['username']; 
    // Get the logged-in user's email from the session.
    
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?"); 
    // Prepare an SQL statement to fetch the password hash for the user based on their email.
    
    $stmt->bind_param("s", $email); 
    // Bind the user's email as a string parameter to the SQL query.
    
    $stmt->execute(); 
    // Execute the prepared statement.
    
    $result = $stmt->get_result(); 
    // Get the result of the query.
    
    $user = $result->fetch_assoc(); 
    // Fetch the associative array containing the user's data (specifically the password).
    
    $stmt->close(); 
    // Close the prepared statement to free up resources.

    // Check if the current password entered by the user matches the stored password hash
    if (password_verify($current_password, $user['password'])) { 
        // Use password_verify to check if the entered current password matches the stored hash.

        if ($new_password === $confirm_password) { 
            // Check if the new password and its confirmation match.
            
            // Hash the new password and update it in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); 
            // Hash the new password using the default hashing algorithm (usually bcrypt).
            
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?"); 
            // Prepare an SQL statement to update the user's password in the database.
            
            $stmt->bind_param("ss", $hashed_password, $email); 
            // Bind the new password hash and the user's email to the SQL query as string parameters.

            if ($stmt->execute()) { 
                // Execute the prepared statement to update the password.
                echo "<script>alert('Password updated successfully!');</script>"; 
                // Display a success message using a JavaScript alert.
            } else { 
                // If there's an error executing the update:
                echo "Error updating password: " . $conn->error; 
                // Display the error message.
            }

            $stmt->close(); 
            // Close the prepared statement.
        } else { 
            // If the new password and confirmation do not match:
            echo "<script>alert('New passwords do not match.');</script>"; 
            // Display an error message using a JavaScript alert.
        }
    } else { 
        // If the current password entered by the user is incorrect:
        echo "<script>alert('Current password is incorrect.');</script>"; 
        // Display an error message using a JavaScript alert.
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding for the document to UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ensure the webpage is responsive to different screen sizes -->
    <title>Profile - Change Password</title> <!-- Set the title of the webpage -->

    <!-- Link to external CSS for styling the profile page -->
    <link rel="stylesheet" href="./student-css/profile-style.css">
    
    <!-- Link to the Font Awesome library for using icons in the HTML -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<header>
    <div class="header-container">
        <!-- Display the system's name/logo in the header -->
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> 
        <nav>
            <ul>
                <!-- Navigation link to the dashboard page -->
                <li><a href="dashboard.php"> HOME </i> </a></li>
                <!-- Navigation link to the teacher's profile page -->
                <li><a href="teacher-profile.php"> MY ACCOUNT </i> </a></li>
                <!-- Navigation link to log out, redirecting to the main page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> 
            </ul>
        </nav>
    </div>
</header>

<!-- Add spacing before the main content for layout purposes -->
<br><br><br>

<main>
    <div class="profile-container">
        <h2>Change Password</h2> <!-- Header for the password change section -->
        <br>
        <form action="" method="post"> 
            <!-- Form for submitting the password change request -->
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required> 
            <!-- Input field for the current password, marked as required -->
            
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required> 
            <!-- Input field for the new password, marked as required -->
            
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required> 
            <!-- Input field to confirm the new password, marked as required -->
            
            <br>
            <button type="submit">Update Password</button> 
            <!-- Submit button for the form -->
            <br>
        </form>
    </div>
</main>

<!-- Add spacing before the footer for layout purposes -->
<br><br><br><br>

<footer>
    <div class="footer-container">
        <!-- Footer content, displaying the copyright notice -->
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> 
    </div>
</footer>

</body>
</html>
