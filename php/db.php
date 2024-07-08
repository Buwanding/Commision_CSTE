<?php
$conn = mysqli_connect("localhost", "root", "", "");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$database = "CSTE";
$query = "CREATE DATABASE IF NOT EXISTS $database";
if (mysqli_query($conn, $query)) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

mysqli_select_db($conn, $database);

// Create users table if not exists
$query = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    contact_number VARCHAR(15) NOT NULL,
    role VARCHAR(10) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if (mysqli_query($conn, $query)) {
    echo "Users table created successfully or already exists.<br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn) . "<br>";
}

// Create subjects table if not exists
$query = "CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    username VARCHAR(50) NOT NULL,
    subject_name VARCHAR(255) NOT NULL,
    subject_color VARCHAR(7) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (username) REFERENCES users(email)
)";
if (mysqli_query($conn, $query)) {
    echo "Subjects table created successfully or already exists.<br>";
} else {
    echo "Error creating subjects table: " . mysqli_error($conn) . "<br>";
}
$query = "CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    username VARCHAR(50) NOT NULL,
    subject_name VARCHAR(255) NOT NULL,
    subject_color VARCHAR(7) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (username) REFERENCES users(email)
)";
if (mysqli_query($conn, $query)) {
    echo "Subjects table created successfully or already exists.<br>";
} else {
    echo "Error creating subjects table: " . mysqli_error($conn) . "<br>";
}

// mysqli_close($conn);
?>

