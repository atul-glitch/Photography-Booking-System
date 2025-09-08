<!-- delete_container.php -->
<?php
session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect admin to the admin login page if not logged in
    header("Location: admin_login.php");
    exit();
}

// Your database connection code here
include 'db_connection.php';
// Check if the container ID is provided in the URL
if (isset($_GET['container_id'])) {
    $container_id = $_GET['container_id'];

    // Delete the container record from the database
    $sql = "DELETE FROM photography_containers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $container_id);
    $stmt->execute();
}

// Redirect back to admin_dashboard.php after the deletion
header("Location: admin_dashboard.php");
exit();
?>