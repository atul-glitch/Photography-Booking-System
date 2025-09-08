<!-- db_connection.php -->
<?php
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$database = 'photography_booking_system';

// Create the database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
