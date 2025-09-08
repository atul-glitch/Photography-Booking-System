<?php
session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect admin to the admin login page if not logged in
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST["booking_id"];
    $status = filter_var($_POST["status"], FILTER_SANITIZE_STRING);

    // Your database connection code here
    include 'db_connection.php';
    // Update the booking status in the database
    $sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: admin_dashboard.php");
    exit();
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
