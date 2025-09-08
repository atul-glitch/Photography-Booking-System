<!-- authenticate.php -->
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
            // echo "<p class='align-middle'>Invalid password. Please try again or <a class='btn btn-primary btn-xs' href='signup.php'>Signup</a></p>";
            $message = 'Invalid password. Please try again or Signup ';
        
        echo "<SCRIPT> 
        alert('$message ');
        </SCRIPT>";

        }
    } else {
        $message = 'Invalid username. Please try again or Signup ';
        
        echo "<SCRIPT> 
        alert('$message ');
        </SCRIPT>";
    }

    $stmt->close();
}
?>



<!-- login.php -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form </title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <style>
        .container-card {
        max-width: 500px;
        padding: 30px;
        border: 2px #ccc;
        border-color: 3px 3px 15px rgba(6, 143, 223, 0.3);
        box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
        border-radius: 10px;
        float: center;
        margin: 0 auto;
        margin-bottom: 100px;
    }
    .form-floating{
        margin: 30px 0;
    }
        

        .header {
            display: flex;
            border: 1px;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 3px 3px 15px rgba(6, 143, 223, 0.3);
        }

        h1,
        h2,p {
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
    <div class="header">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">
                    <h3>Home</h3>
                </a>
            </li>
        </ul>
    </div>
        <h1>Login</h1>
      <div class="container  ">
    <main class="form-signin w-100 m-auto">
        <div class="container-card hvr-grow-shadow">
       
        <form action="" method="post">
            <div class="form-floating">
                <input type="text" class="form-control" placeholder="Username" name="username" required>
                <label for="username">Username</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
                <label for="password">Password</label>
            </div>

            <!-- <div class="pass">Forgot Password?</div> -->
            <input class="btn btn-primary w-100 py-2" type="submit" value="Login">
            <div ><p>Create account. <a href="signup.php">Signup.</a><br>
                Login as <a href="admin_login.php">Admin</a></p>
            </div>
        </form>
        </div>
</main>
</div>
        <footer class="my-5 pt-5 text-body-secondary text-center text-small">
            <p class="mb-1">©
                <script>document.write(new Date().getFullYear())</script> AAR PHOTOGRAPHY
            </p>
            <ul class="list-inline">
            </ul>
        </footer>
    </div>

</body>


</html>