<!-- delete_user.php -->
<?php
session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect admin to the admin login page if not logged in
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["user_id"])) {
        // Your database connection code here
        include 'db_connection.php';

        // Get the user ID from the URL parameter
        $user_id = $_GET["user_id"];

        // Delete the user's bookings first
        $delete_bookings_query = "DELETE FROM bookings WHERE user_id = ?";
        $stmt = $conn->prepare($delete_bookings_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Now, delete the user from the users table
        $delete_user_query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($delete_user_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Close the statement and database connection
        $stmt->close();
        $conn->close();

        // Redirect back to admin_dashboard.php after deleting the user
        header("Location: admin_dashboard.php");
        exit();
    }
}
?>