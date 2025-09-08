<!-- admin_dashboard.php -->
<?php
session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect admin to the admin login page if not logged in
    header("Location: admin_login.php");
    exit();
}

// Your database connection code here
include 'db_connection.php';
// Assuming you have a database connection $conn

// Handling the form submission for container upload
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["submit_upload"])) {
        $container_title = $_POST["container_title"];
        $container_price = $_POST["container_price"];
        $container_description = $_POST["container_description"];

        // Process the uploaded image
        if (isset($_FILES["container_photo"]) && $_FILES["container_photo"]["error"] === UPLOAD_ERR_OK) {
            $image_name = $_FILES["container_photo"]["name"];
            $temp_name = $_FILES["container_photo"]["tmp_name"];
            $image_path = "container_images/" . $image_name;

            // Move the uploaded image to the destination folder
            move_uploaded_file($temp_name, $image_path);
        } else {
            // You can handle the case when an image is not uploaded or there's an error
            // For simplicity, we'll assume an image is always uploaded.
        }

        // Insert the container details into the database
        $sql = "INSERT INTO photography_containers (title, price,  photo_url) VALUES (?, ?,  ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $container_title, $container_price, $image_path);
        $stmt->execute();

        // Redirect back to admin_dashboard.php after the form submission
        header("Location: admin_dashboard.php");
        exit();
    }
}

// Fetch all users from the database
$user_query = "SELECT * FROM users";
$user_result = $conn->query($user_query);

if ($user_result->num_rows > 0) {
    $users = $user_result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}




// Fetch all bookings along with payment status from the database
$search_user_id = null;
if (isset($_GET["search_user_id"])) {
    $search_user_id = $_GET["search_user_id"];
}

$sql = "SELECT b.*, c.title, c.price AS total_price, u.username AS user_name FROM bookings b
        INNER JOIN photography_containers c ON b.container_id = c.id
        INNER JOIN users u ON b.user_id = u.id";
if ($search_user_id) {
    // If a user ID is provided in the search bar, filter the results by user ID
    $sql .= " WHERE b.user_id = ?";
}

$stmt = $conn->prepare($sql);
if ($search_user_id) {
    $stmt->bind_param("i", $search_user_id);
}
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any bookings
if ($result->num_rows > 0) {
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $bookings = [];
}

// Close the statement and free up resources
$stmt->close();

// Fetch all containers before closing the connection
$container_query = "SELECT * FROM photography_containers";
$container_result = $conn->query($container_query);

// Check if there are any containers
if ($container_result->num_rows > 0) {
    $containers = $container_result->fetch_all(MYSQLI_ASSOC);
} else {
    $containers = [];
}

// Close the connection
$conn->close();
?>

<!-- $stmt->close();
$conn->close();

?> -->

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin dsahboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <title>User Dashboard</title>
</head>
<style>
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 30px;
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
        margin-bottom: 70px;
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

    .input-group {
        width: 30%;
        margin: 20px;
    }
</style>

<body>
    <div class="header">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active " aria-current="page" href="index.php">
                    <h3>Home</h3>
                </a>
            </li>

            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active " aria-current="page" href="logout.php">
                        <h3>Logout</h3>
                    </a>
                </li>
    </div>
    <h1>ADMIN DASHBOARD</h1>
    <h2>Welcome,
        <?php echo $_SESSION["admin_username"]; ?>
    </h2>

    <!-- Form to upload new photography containers -->
    <div class="container ">
        <div class="container-card container-xl">
            <h4>Upload New Photography Container</h4>
            <form action="admin_dashboard.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="container_title" class="form-label">Container Title</label>
                    <input type="text" class="form-control" id="container_title" name="container_title" required>
                </div>
                <div class="mb-3">
                    <label for="container_price" class="form-label">Container Price</label>
                    <input type="number" class="form-control" id="container_price" name="container_price" step="0.01"
                        required>
                </div>
                <div class="mb-3">
                    <label for="container_description" class="form-label">Container Description</label>
                    <textarea class="form-control" id="container_description" name="container_description"
                        required></textarea>
                </div>
                <div class="mb-3">
                    <label for="container_photo" class="form-label">Container Photo</label>
                    <input type="file" class="form-control" id="container_photo" name="container_photo" required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit_upload">Upload Container</button>
            </form>


        </div>
    </div>

    <!-- Editing container table -->
    <h4>Edit Photography Container:</h4>
    <div class="header ">

        <?php if (!empty($containers)): ?>
            <table border="1" class="table table-bordered border-primary table-hover table-striped">
                <thead class="table-info">
                    <tr>
                        <th scope="col">S.No.</th>
                        <th scope="col">Container ID</th>
                        <th scope="col">Container Title</th>
                        <th scope="col">Container Price</th>
                        <th scope="col">Container Description</th>
                        <th scope="col">Container Photo</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    foreach ($containers as $container):

                        ?>
                        <tr>
                            <td>
                                <?php echo $counter; ?>
                            </td>
                            <td>
                                <?php echo $container['id']; ?>
                            </td>
                            <td>
                                <?php echo $container['title']; ?>
                            </td>
                            <td>
                                <?php echo $container['price']; ?>
                            </td>
                            <td>
                                <?php echo $container['description']; ?>
                            </td>
                            <td>
                                <img src="<?php echo $container['photo_url']; ?>" alt="Container Photo"
                                    style="width: 100px; height: 100px;">
                            </td>
                            <td>
                                <a href="edit_container.php?container_id=<?php echo $container['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="delete_container.php?container_id=<?php echo $container['id']; ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this container?')">Delete</a>

                            </td>
                        </tr>
                        <?php
                        $counter++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No containers found.</p>
        <?php endif; ?>
    </div>
    <!-- ending editing -->

    <!-- user table -->
    <h4>Delete user account:</h4>
    <div class="header">
        <?php if (!empty($users)): ?>
            <table border="1" class="table table-bordered border-primary table-hover table-striped">
                <thead class="table-info">
                    <tr>
                        <th scope="col">S.No.</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    foreach ($users as $user):
                        ?>
                        <tr>
                            <td>
                                <?php echo $counter; ?>
                            </td>
                            <td>
                                <?php echo $user['id']; ?>
                            </td>
                            <td>
                                <?php echo $user['username']; ?>
                            </td>
                            <td>
                                <?php echo $user['email']; ?>
                            </td>
                            <td>
                                <a href="delete_user.php?user_id=<?php echo $user['id']; ?>" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this user? Deleting a user will also delete their bookings.')">Delete
                                    User</a>
                            </td>
                        </tr>
                        <?php
                        $counter++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
    <!-- ending user table -->


    <!-- booking table -->
    <h4>User Bookings:</h4>
    <!-- Add a search form to search bookings by user ID -->

    <form action="admin_dashboard.php" method="get">
        <!-- <label for="search_user_id">Search by User ID:</label> -->
        <div class="input-group input-group-sm mb-3">
            <span for="search_user_id" class="input-group-text" id="inputGroup-sizing-sm">Search by User ID:</span>
            <input type="text" id="search_user_id" name="search_user_id" class="form-control"
                aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
            <input type="submit" class="btn btn-primary"
                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                value="Search">
        </div>
    </form>

    <div class="header">
        <?php if (!empty($bookings)): ?>
            <table border="1" class="table table-bordered border-primary  table-hover table-striped">
                <thead class="table-info">
                    <tr>
                        <th scope="col">S.No.</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Container</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Event Address</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Status</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td class="count"></td>
                            <td>
                                <?php echo $booking["user_id"]; ?>
                            </td>
                            <td>
                                <?php echo $booking["container_id"]; ?>
                            </td>
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
                                <form action="update_booking_status.php" method="post">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking["id"]; ?>">
                                    <select name="status">
                                        <option value="pending">Pending</option>
                                        <option value="accepted">Accepted</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                    <input type="submit" class="btn btn-primary"
                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                                        value="Update">
                                </form>
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