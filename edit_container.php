<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["submit_update"])) {
        $container_id = $_POST["container_id"];
        $container_title = $_POST["container_title"];
        $container_price = $_POST["container_price"];
        $container_description = $_POST["container_description"];

        // Process the uploaded image if provided
        if (isset($_FILES["container_photo"]) && $_FILES["container_photo"]["error"] === UPLOAD_ERR_OK) {
            $image_name = $_FILES["container_photo"]["name"];
            $temp_name = $_FILES["container_photo"]["tmp_name"];
            $image_path = "container_images/" . $image_name;
            move_uploaded_file($temp_name, $image_path);
        } else {
            // If no new image is uploaded, keep the existing image path
            $image_path = $_POST["existing_photo"];
        }

        $sql = "UPDATE photography_containers 
                SET title = ?, price = ?, description = ?, photo_url = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssi", $container_title, $container_price, $container_description, $image_path, $container_id);
        $stmt->execute();

        header("Location: admin_dashboard.php");
        exit();
    }
}

if (isset($_GET["container_id"])) {
    $container_id = $_GET["container_id"];
    $container_query = "SELECT * FROM photography_containers WHERE id = ?";
    $container_stmt = $conn->prepare($container_query);
    $container_stmt->bind_param("i", $container_id);
    $container_stmt->execute();
    $container_result = $container_stmt->get_result();

    if ($container_result->num_rows === 1) {
        $container = $container_result->fetch_assoc();
    } else {
        header("Location: admin_dashboard.php");
        exit();
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Container</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .header {
        display: flex;
        border: 1px;
        margin: 15px;
        border-radius: 10px;
        box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
    }

    h1 {
        text-align: center;
        color: rgb(13, 116, 156);
        padding: 20px 0 20px;
        font-family: initial;

    }

    h3,
    p {
        color: rgb(13, 116, 156);
        font-family: initial;
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
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link active " aria-current="page" href="admin_dashboard.php">
                            <h3>Dashboard</h3>
                        </a>
                    </li>
    </div>
    <div class="container mt-5">
        <h1>Edit Container</h1>
        <form action="edit_container.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="container_id" value="<?php echo $container['id']; ?>">
            <input type="hidden" name="existing_photo" value="<?php echo $container['photo_url']; ?>">
            <div class="mb-3">
                <label for="container_title" class="form-label">Container Title</label>
                <input type="text" class="form-control" id="container_title" name="container_title" required
                    value="<?php echo $container['title']; ?>">
            </div>
            <div class="mb-3">
                <label for="container_price" class="form-label">Container Price</label>
                <input type="number" class="form-control" id="container_price" name="container_price" step="0.01"
                    required value="<?php echo $container['price']; ?>">
            </div>
            <div class="mb-3">
                <label for="container_description" class="form-label">Container Description</label>
                <textarea class="form-control" id="container_description" name="container_description"
                    required><?php echo isset($container['description']) ? $container['description'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="container_photo" class="form-label">Container Photo</label>
                <input type="file" class="form-control" id="container_photo" name="container_photo">
                <p class="text-muted">Leave this blank if you don't want to change the photo.</p>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_update">Update Container</button>
        </form>
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