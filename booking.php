<!-- booking.php -->
<?php
session_start();

if (!isset($_SESSION["username"])) {
    // Redirect users to the login page if they are not logged in
    header("Location: login.php");
    exit();
}

// Your database connection code here
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["container_id"])) {
    $container_id = $_GET["container_id"];

    // Fetch container details from the database
    $sql = "SELECT * FROM photography_containers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $container_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $container = $result->fetch_assoc();
    } else {
        // Redirect users to the homepage if the container is not found
        header("Location: homepage.php");
        exit();
    }
} else {
    // Redirect users to the homepage if container_id is not provided in the URL
    header("Location: homepage.php");
    exit();
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 50px;



        }

        .container-card {
            max-width: 800px;
            padding: 15px;
            border: 2px #ccc;
            border-color: 3px 3px 15px rgba(6, 143, 223, 0.3);
            box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
            border-radius: 10px;
            float: center;
            margin: 0 auto;
            margin-bottom: 100px;

        }

        .container-card img {
            width: 100%;
            height: 600px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;

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


        h3,
        h5 {
            color: rgb(13, 116, 156);
            font-family: initial;
        }
        .hvr-grow-shadow {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  border-color: 3px 3px 15px rgba(6, 143, 223, 0.3);
 box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: box-shadow, transform;
  transition-property: box-shadow, transform;
}
.hvr-grow-shadow:hover, .hvr-grow-shadow:focus, .hvr-grow-shadow:active {
  box-shadow: 5px 10px 20px 20px rgba(6, 143, 223, 0.3);
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
    </style>
</head>

<body>
    <div class="header hvr-grow-shadow">
        <ul class="nav ">
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="homepage.php">
                    <h3><-- </h3>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="index.php">
                    <h3>Home</h3>
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

    <h1>BOOK NOW</h1>

    <h2>Photography type:
        <?php echo $container['title']; ?>
    </h2>
    <div class="container">
        <div class="container-card">
            <img src="<?php echo $container['photo_url']; ?>" alt="<?php echo $container['title']; ?>">
            <h5>Price: ₹
                <?php echo $container['price']; ?>
            </h5><br>
            <!-- Your booking form goes here -->
            <form action="confirm_booking.php" method="post">
                <input type="hidden" name="container_id" class="form-control" value="<?php echo $container["id"]; ?>">
                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" class="form-control" required><br>
                <label for="event_address">Event Address:</label>
                <input type="text" id="event_address" name="event_address" class="form-control" required><br>
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" class="form-control" required><br>
                <label for="booking_date">Date:</label>
                <input type="date" id="booking_date" name="booking_date" class="form-control" required><br>
                <label for="booking_time">Time:</label>
                <input type="time" id="booking_time" name="booking_time" class="form-control" required><br>
                <label for="time_period">Time Period:</label>
                <select name="time_period" id="time_period" required>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select><br><br>
                <input type="submit" class="btn btn-primary" value="Book Now">
            </form>
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