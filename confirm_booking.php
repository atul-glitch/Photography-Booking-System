<?php
session_start();

if (!isset($_SESSION["username"])) {
    // Redirect users to the login page if they are not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"]; 
    $container_id = $_POST["container_id"];
    $event_name = $_POST["event_name"];
    $event_address = $_POST["event_address"];
    $phone_number = $_POST["phone_number"];
    $booking_date = $_POST["booking_date"];
    $booking_time = $_POST["booking_time"];
    $time_period = $_POST["time_period"];
    

    // Your database connection code here
    include 'db_connection.php';

    

    // Verify that the user_id exists in the users table
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Invalid user ID.";
        exit();
    }

    // Verify that the container_id exists in the photography_containers table
    $sql = "SELECT * FROM photography_containers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $container_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Invalid container ID.";
        exit();
    }

    // Insert the booking details into the bookings table
    $sql = "INSERT INTO bookings (user_id, container_id, event_name, event_address, phone_number, booking_date, booking_time, time_period) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssss", $user_id, $container_id, $event_name, $event_address, $phone_number, $booking_date, $booking_time, $time_period);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $success_msg = "Booking confirmed successfully. You will receive a confirmation shortly. Click below to check";
    $dashboar_btn = "<a href='user_dashboard.php' class='btn btn-primary px-5 mb-5' type='button'>Dashboard</a>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .container {
            box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
            border-radius: 30px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-5">

            <h1 class="text-body-emphasis">Thank you for booking</h1>
            <p class="col-lg-6 mx-auto mb-4">
                <?php echo $success_msg; ?>
            </p>
            <?php echo $dashboar_btn; ?>
        </div>
    </div>

</body>
<footer class="my-5 pt-5 text-body-secondary text-center text-small">
    <p class="mb-1">©
        <script>document.write(new Date().getFullYear())</script> AAR PHOTOGRAPHY
    </p>
    <ul class="list-inline">
    </ul>
</footer>

</html>