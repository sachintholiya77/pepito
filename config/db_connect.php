<?php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "pepito";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Optional: Display a success message for testing, but comment out in production
// echo "Connected successfully";
?>