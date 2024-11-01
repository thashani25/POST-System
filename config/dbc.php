<?php
// Database connection configuration
$servername = "localhost";  // Replace with your server name if different
$username = "root";         // Replace with your MySQL username
$password = "";             // Replace with your MySQL password
$dbname = "post_php";  // Replace with your actual database name

// Enable or disable debug mode
$debug = false; // Set to true during development to see debug messages

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // If the connection fails, display the error
    die("Connection failed: " . $conn->connect_error);
}

// Debug option: If debug mode is enabled, show a connection success message
if ($debug) {
    echo "DEBUG: Successfully connected to the database";
}


?>
