<?php
// Database configuration
$servername = "Mysql@127.0.0.1:3306";
$username = "root"; // Replace with your database username
$password = "merlin123@nan"; // Replace with your database password
$dbname = "techie_crew"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Email configuration
$to_email = "merlinjose51@gmail.com"; // Your email address
$subject_prefix = "Techie Crew - ";
?>