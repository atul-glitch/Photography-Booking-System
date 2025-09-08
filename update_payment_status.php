<?php
// Your database connection code here
include 'db_connection.php';

// Assuming you have a database connection $conn

// Handle the payment response from AJAX
if (isset($_POST["payment_id"]) && isset($_POST["payment_status"]) && isset($_POST["booking_id"])) {
    $payment_id = $_POST["payment_id"];
    $payment_status = $_POST["payment_status"];
    $booking_id = $_POST["booking_id"];

    // Check the actual payment status received from Razorpay
    if ($payment_status === "Authorized") {
        // Payment is successful, update the payment_status in the bookings table
        $sql = "UPDATE bookings SET payment_status = 'success' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        // Check if the payment status was updated successfully
        $success = $stmt->affected_rows > 0;
        $stmt->close();

        // Return success response to AJAX call
        echo json_encode(array("success" => $success));
    } else {
        // Payment failed, update the payment_status in the bookings table
        $sql = "UPDATE bookings SET payment_status = 'failed' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        // Check if the payment status was updated successfully
        $success = $stmt->affected_rows > 0;
        $stmt->close();

        // Return success response to AJAX call
        echo json_encode(array("success" => $success));
    }
} else {
    // Return failure response to AJAX call
    echo json_encode(array("success" => false));
}

$conn->close();
?>