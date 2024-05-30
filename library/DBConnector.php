<!-- This file was reused from a previous lab -->
<?php
$servername = "localhost";
$username = "username";
$password = "";
$dbname = "librarytest";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql_create_db);

// Select database
$conn->select_db($dbname);

//***NO LONGER NECESSARY BECAUSE OF SQL FILE***
// Create Users table if it doesn't exist
$sql_create_users_table = "CREATE TABLE IF NOT EXISTS users (
    user_ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

$conn->query($sql_create_users_table);
?>
