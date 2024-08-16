<?php
session_start(); // Start the session to track user data across pages

require '../php/db.php'; // Include the database connection script

// Check if the user is not logged in (i.e., no username in session)
if (!isset($_SESSION['username'])) {
    header("Location: ../index.html"); // Redirect to login page if not logged in
    exit(); // Stop further execution
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted via POST method
    $current_password = $_POST["current_password"]; // Get the current password from the form
    $new_password = $_POST["new_password"]; // Get the new password from the form
    $confirm_password = $_POST["confirm_password"]; // Get the confirmation of the new password from the form
    
    // Fetch the user's current password hash from the database
    $email = $_SESSION['username']; // Get the user's email from the session
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?"); // Prepare the SQL statement to get the password
    $stmt->bind_param("s", $email); // Bind the user's email to the SQL statement
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result of the query
    $user = $result->fetch_assoc(); // Fetch the user's current password hash
    $stmt->close(); // Close the statement

    // Check if the current password matches the stored password hash
    if (password_verify($current_password, $user['password'])) { 
        // Verify that the current password matches the hash in the database
        if ($new_password === $confirm_password) { // Check if the new password matches the confirmation
            // Hash the new password and update it in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?"); // Prepare the SQL statement for updating the password
            $stmt->bind_param("ss", $hashed_password, $email); // Bind the hashed password and email to the SQL statement

            if ($stmt->execute()) { // Execute the update query
                echo "<script>alert('Password updated successfully!');</script>"; // Alert the user if the update was successful
            } else {
                echo "Error updating password: " . $conn->error; // Display an error message if the update fails
            }

            $stmt->close(); // Close the statement
        } else {
            echo "<script>alert('New passwords do not match.');</script>"; // Alert the user if the new passwords do not match
        }
    } else {
        echo "<script>alert('Current password is incorrect.');</script>"; // Alert the user if the current password is incorrect
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport to make the page responsive -->
    <title>Profile - Change Password</title> <!-- Set the title of the page -->
    <link rel="stylesheet" href="./teacher-styles/profile-style.css"> <!-- Link to the external CSS file for styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Link to Font Awesome icons -->
</head>
<body>

<header>
    <div class="header-container">
        <label class="logo">STUDENT ACTIVITY MANAGEMENT SYSTEM</label> <!-- System logo in the header -->
        <nav>
            <ul>
                <li><a href="dashboard.php"> HOME </i> </a></li> <!-- Link to the home page -->
                <li><a href="teacher-profile.php"> ACCOUNT </i> </a></li> <!-- Link to the account/profile page -->
                <li><a href="../index.html" class="logout">LOGOUT</a></li> <!-- Link to log out -->
            </ul>
        </nav>
    </div>
</header>

<br><br><br>

<main>
    <div class="profile-container">
        <h2>Change Password</h2> <!-- Heading for the change password section -->
        <br>
        <form action="" method="post"> <!-- Form for updating the password, submitting to the same page -->
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required> <!-- Input field for current password -->

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required> <!-- Input field for new password -->

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required> <!-- Input field to confirm the new password -->
            <br>
            <button type="submit">Update Password</button> <!-- Button to submit the form -->
            <br>
        </form>
    </div>
</main>

<br><br><br><br>

<footer>
    <div class="footer-container">
        <p>&copy; 2024 Student Activity Management System (SAMS). All rights reserved.</p> <!-- Footer content -->
    </div>
</footer>

</body>
</html>
