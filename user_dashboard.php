<?php
session_start();

if (!isset($_SESSION["username"])) {
    // Redirect users to the login page if they are not logged in
    header("Location: login.php");
    exit();
}

// Function to fetch user bookings from the database
function fetchUserBookings($user_id)
{
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'photography_booking_system';

    // Create the database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Fetch user bookings from the database
    $sql = "SELECT b.*, c.title, c.price AS total_price FROM bookings b
            INNER JOIN photography_containers c ON b.container_id = c.id
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any bookings
    if ($result->num_rows > 0) {
        $bookings = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $bookings = [];
    }

    $stmt->close();
    $conn->close();

    return $bookings;
}

// Fetch user bookings from the database
$bookings = fetchUserBookings($_SESSION["user_id"]);

function deleteBooking($booking_id)
{

    include 'db_connection.php';

    // Delete the booking from the database
    $sql = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // Check if the booking was deleted successfully
    $success = $stmt->affected_rows > 0;

    $stmt->close();
    $conn->close();

    return $success;
}

// Check if the "Delete" button was clicked
if (isset($_GET["action"]) && $_GET["action"] === "delete" && isset($_GET["booking_id"])) {
    $booking_id = $_GET["booking_id"];
    // Call the deleteBooking function to delete the booking
    if (deleteBooking($booking_id)) {
        // Redirect back to the user dashboard after successful deletion
        header("Location: user_dashboard.php");
        exit();
    }
}
?>




<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <title>User Dashboard</title>
    <style>
        table {
            counter-reset: section;

        }

        .count:before {
            counter-increment: section;
            content: counter(section);
        }




        .header {
            display: flex;
            border: 1px;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
        }

        h1,
        h2 {
            text-align: center;
            color: rgb(13, 116, 156);
            padding: 20px 0 20px;
            font-family: initial;

        }

        h4 {
            margin: 10px 20px;
            color: rgb(13, 116, 156);
            font-family: initial;
        }

        p,
        h5,
        th,
        td,
        h3 {
            color: rgb(13, 116, 156);
            font-family: initial;
        }
    </style>
</head>

<body>
    <div class="header">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active " aria-current="page" href="index.php">
                    <h3>Home</h3>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="homepage.php">
                    <h3>Booking</h3>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="logout.php">
                    <h3>Logout</h3>
                </a>
            </li>
        </ul>
    </div>
    <h1> DASHBOARD</h1>
    <h2>Welcome,
        <?php echo $_SESSION["username"]; ?>
    </h2>

    <h4>Your Bookings</h4>
    <div class="header">
        <?php if (!empty($bookings)): ?>
            <table border="1" class="table table-bordered border-primary  table-hover table-striped">
                <thead class="table-info">
                    <tr>
                        <!-- <th>Container</th> -->
                        <th scope="col">S.No.</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Event Address</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Status</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Action</th>
                        <th scope="col">Delete</th>

                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach ($bookings as $booking): ?>

                        <tr>
                            <td class="count"></td>
                            <td>
                                <?php echo $booking["event_name"]; ?>
                            </td>
                            <td>
                                <?php echo $booking["event_address"]; ?>
                            </td>
                            <td>
                                <?php echo $booking["phone_number"]; ?>
                            </td>
                            <td>
                                <?php echo $booking["booking_date"]; ?>
                            </td>
                            <td>
                                <?php echo $booking["booking_time"]; ?> <?php echo $booking["time_period"]; ?>
                              </td>
                            <td>
                                <?php echo $booking["status"]; ?>
                            </td>
                            <td>
                                <?php echo $booking["payment_status"]; ?>
                            </td>
                            <td>
                                <?php if ($booking["status"] === "accepted" && $booking["payment_status"] !== "success"): ?>
                                    <a class="btn btn-primary" href="payment.php?booking_id=<?php echo $booking["id"]; ?>">Pay
                                        Now</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-danger"
                                    href="user_dashboard.php?action=delete&booking_id=<?php echo $booking["id"]; ?>"
                                    onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
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