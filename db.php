<?php
// Database configuration
$servername = "localhost";
$username = "u432434733_cb";       // MySQL default username for XAMPP
$password = "Aduseradcb1";           // MySQL default password for XAMPP is empty
$dbname = "u432434733_ad";         // Database name we created

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
