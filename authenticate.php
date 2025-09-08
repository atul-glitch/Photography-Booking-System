<!-- user login authenticate.php -->
<?php
session_start();

// Include the database connection code
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user input
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = $_POST["password"];

    // Fetch user from the database by username
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user["password"])) {
            // Password is correct, set user_id in the session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Redirect the user to the homepage or any other authorized page
            header("Location: homepage.php");
            exit();
        } else {
            echo "Invalid password. Please try again.";
        }
    } else {
        echo "Invalid username. Please try again.";
    }

    $stmt->close();
}
?>
