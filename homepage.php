<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Photography Containers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
            margin-top:40px;
        }

        .container-card {
            max-width: 300px;
            padding: 15px;
            border: 2px #ccc;
            border-color: 3px 3px 15px rgba(6, 143, 223, 0.3);
            box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
            border-radius: 10px;
        }

        .container-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        h1 {
            text-align: center;
            color: rgb(13, 116, 156);
            padding: 30px 0 30px;
            font-family: initial;
        }

        .header {
            display: flex;
            border: 1px;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
        }

        h3,
        p,
        h5 {
            color: rgb(13, 116, 156);
            font-family: initial;
        }

        .input-group {
           
            width: 30%;
            margin: 30px ;
        }
        .hvr-grow-shadow {
 
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
 box-shadow: 5px 5px 15px 15px rgba(6, 143, 223, 0.3);
 -webkit-transform: scale(1.1);
 transform: scale(1.1);
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
    <h1>CHOOSE WHICH IS BEST FOR YOU</h1>

    <form action="homepage.php" method="get">
        <!-- <label for="search_user_id">Search by User ID:</label> -->
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" id="inputGroup-sizing-sm">Search:</span>
            <input type="text" name="search" class="form-control" aria-label="Sizing example input"
                aria-describedby="inputGroup-sizing-sm " placeholder="Search by photography" required>
            <input type="submit" class="btn btn-info"
                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                value="Search">
            <a class="btn btn-info" href="homepage.php">All</a>
        </div>
    </form>
    <div class="container">
        <?php
        // Your database connection code here
        include 'db_connection.php';
        // Assuming you have a database connection $conn
        
        // Fetch photography containers based on search query
        if (isset($_GET["search"])) {
            $search_query = "%" . $_GET["search"] . "%";

            $sql = "SELECT * FROM photography_containers WHERE title LIKE ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $search_query);
        } else {
            // Fetch all photography containers
            $sql = "SELECT * FROM photography_containers";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Display the photography containers
            while ($row = $result->fetch_assoc()) {
                echo "<div class='container-card hvr-grow-shadow '>";
                echo "<img src='" . $row["photo_url"] . "' alt='" . $row["title"] . "'>";
                echo "<h5>" . $row["title"] . "</h5>";
                echo "<p>" . $row["description"] . "</p>";

                echo "<p>Price: ₹" . $row["price"] . "</p>";
                // Book Now button
                echo "<a class='btn btn-primary' href='booking.php?container_id=" . $row["id"] . "'>Book Now</a>";
                echo "</div>";
            }
        } else {
            echo "No photography containers found.";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
    <footer class="my-5 pt-5 text-body-secondary text-center text-small">
        <p class="mb-1">©
            <script>document.write(new Date().getFullYear())</script> AAR PHOTOGRAPHY
        </p>
        <ul class="list-inline">
        </ul>
    </footer>
</body>

</html>