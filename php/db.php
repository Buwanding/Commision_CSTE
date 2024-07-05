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
    email VARCHAR(50) NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    role VARCHAR(10) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if (mysqli_query($conn, $query)) {
    echo "Table created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}
?>
