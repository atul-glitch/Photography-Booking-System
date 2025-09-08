<?php
session_start();

// Your database connection code here
include 'db_connection.php';

// Fetch the booking details for the payment from the database
if (isset($_GET["booking_id"])) {
    $booking_id = $_GET["booking_id"];

    // Fetch booking details along with the container price
    $sql = "SELECT b.*, c.price AS total_price FROM bookings b
            INNER JOIN photography_containers c ON b.container_id = c.id
            WHERE b.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $booking = $result->fetch_assoc();
    } else {
        // Redirect users to the user dashboard if the booking is not found
        header("Location: user_dashboard.php");
        exit();
    }

    $stmt->close();
} else {
    // Redirect users to the user dashboard if the booking ID is not provided
    header("Location: user_dashboard.php");
    exit();
}

// Handle the payment response from Razorpay
if (isset($_POST["razorpay_payment_id"])) {
    $payment_id = $_POST["razorpay_payment_id"];
    $payment_status = $_POST["razorpay_payment_status"];

    if ($payment_status === "Authorized") {
        // Payment is successful, update the payment_status in the bookings table
        $sql = "UPDATE bookings SET payment_status = 'success' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
    } else {
        // Payment failed, update the payment_status in the bookings table
        $sql = "UPDATE bookings SET payment_status = 'failed' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
    }

    // Redirect to user dashboard or any other success/failure page
    header("Location: user_dashboard.php");
    exit();
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <style>
        


       

        .container-card {
            max-width: 800px;
            padding: 50px;
            border: 2px #ccc;
            border-color: 3px 3px 15px rgba(6, 143, 223, 0.3);
            box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
            border-radius: 10px;
            float: center;
            margin: 0 auto;
            margin-bottom: 100px;

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
            padding: 30px 0 30px;
            font-family: initial;

        }
 .fs-4{
    color: rgb(13, 116, 156);
            font-family: initial;
 }

        h3,
        h4, p {
            color: rgb(13, 116, 156);
            font-family: initial;
        }
</style>
   
    <!-- Include the Razorpay JavaScript SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
<div class="header">
        <ul class="nav">
           
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="index.php">
                    <h3>Home</h3>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="homepage.php">
                    <h3>Booking </h3>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="user_dashboard.php">
                    <h3>Dashboard</h3>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="logout.php">
                    <h3>Logout</h3>
                </a>
            </li>
        </ul>
    </div>
    <h1>PAYMENT</h1>
    <div class="container-xl ">
    <div class="container-card">
    <h2>Booking Details</h2>
    <ul class="list-group list-group-flush">
  <li class="list-group-item fs-4">Event Name:
        <?php echo $booking["event_name"]; ?></li>
  <li class="list-group-item fs-4">Event Address:
        <?php echo $booking["event_address"]; ?></li>
  <li class="list-group-item fs-4">Phone Number:
        <?php echo $booking["phone_number"]; ?></li>
  <li class="list-group-item fs-4">Date:
        <?php echo $booking["booking_date"]; ?></li>
  <li class="list-group-item fs-4">Time:
        <?php echo $booking["booking_time"]; ?><?php echo $booking["time_period"]; ?></li>
   
    <li class="list-group-item fs-4"><?php if (isset($booking["total_price"])): ?>Total Price:                            
        ₹<?php echo $booking["total_price"]; ?></li>
        <li class="list-group-item fs-4"> <button class="btn btn-primary" id="rzp-button">Pay Now</button></li>
</ul>
    
        
       
    </div>
    </div>
    <script>
            // Function to create a Razorpay order and handle payment success/failure
            function createOrder() {
                // Fetch the total price from PHP variable
                var totalAmount = <?php echo $booking["total_price"]; ?> * 100;

                // Replace with your Razorpay API Key
                var razorpayApiKey = "rzp_test_kPYadqyROEhRCX";

                var options = {
                    key: razorpayApiKey,
                    amount: totalAmount,
                    currency: "INR",
                    name: "Photography Booking System",
                    description: "Payment for booking #" + <?php echo $booking["id"]; ?>,
                    handler: function (response) {
                        // Payment successful
                        alert("Payment successful! Payment ID: " + response.razorpay_payment_id);

                        var paymentData = {
                            payment_id: response.razorpay_payment_id,
                            payment_status: "Authorized", // Assuming "Authorized" is the successful payment status from Razorpay
                            booking_id: <?php echo $booking["id"]; ?>
                        };

                        // Make AJAX call to update payment status
                        updatePaymentStatus(paymentData);
                    },
                    prefill: {
                        name: "<?php echo $_SESSION["username"]; ?>",
                        email: "user@example.com" // You can fetch user email from the session or ask the user to input it
                    }
                };

                var rzp = new Razorpay(options);
                rzp.open();
            }

            // Function to update payment status using AJAX
            function updatePaymentStatus(paymentData) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            // Payment status updated successfully, redirect to the user dashboard
                            window.location.href = "user_dashboard.php";
                        } else {
                            alert("Failed to update payment status. Please try again later.");
                        }
                    }
                };

                xhttp.open("POST", "update_payment_status.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("payment_id=" + paymentData.payment_id + "&payment_status=" + paymentData.payment_status + "&booking_id=" + paymentData.booking_id);
            }

            // Attach click event to the Pay Now button
            document.getElementById("rzp-button").onclick = function () {
                createOrder();
            };
        </script>

    <?php else: ?>
        <p>Total Price not available for this booking.</p>
    <?php endif; ?>
</body>
<footer class="my-5 pt-5 text-body-secondary text-center text-small">
    <p class="mb-1">©
        <script>document.write(new Date().getFullYear())</script> AAR PHOTOGRAPHY
    </p>
    <ul class="list-inline">
    </ul>
</footer>
</html>
