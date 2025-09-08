<?php
// signup.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user input
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash the password
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    // Your database connection code here
    include 'db_connection.php';

    // Check if the username is already taken
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $message = 'Username already exists. Please choose a different username or login ';
        
        echo "<SCRIPT> 
        alert('$message ');
        </SCRIPT>";

    } else {
        // Insert the user into the database
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);
        $stmt->execute();
        
        $message = 'Signup successful.';
    
    echo "<SCRIPT> 
    alert('$message');
    window.location='login.php';
    </SCRIPT>";
    
       
    }

    $stmt->close();
    $conn->close();
}
?>
<!-- signup.php -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form </title>
    
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

    <h1>Signup</h1>
      <div class="container  ">
    <main class="form-signin w-100 m-auto">
        <div class="container-card hvr-grow-shadow">
       
        <form action="" method="post">
            <div class="form-floating">
                <input type="text" class="form-control" placeholder="Username" name="username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" placeholder="E-mail" name="email" required>
                <label for="email">E-mail</label>
            </div>

            <div class="form-floating">
                <input type="password" id="password" class="form-control" placeholder="Password" name="password" required onkeyup='check();'>
                
                <label for="password">Password</label>
            </div>
            <div class="form-floating">
                <input type="password" id="confirm_password" class="form-control" placeholder="Repeat Password" name="confirm_password" required onkeyup='check();'>
                
                <label for="username">Repeat Password</label>
                <span class="align-middle" id='message'></span>
            </div>
            

            <!-- <div class="pass">Forgot Password?</div> -->
            <input class="btn btn-primary w-100 py-2" type="submit" value="Login">
            <div ><p>Already have an Account? <a href="login.php">Login.</a><br>
                </p>
            </div>
        </form>
        </div>
</main>
</body>
<footer class="my-5 pt-5 text-body-secondary text-center text-small">
    <p class="mb-1">©
        <script>document.write(new Date().getFullYear())</script> AAR PHOTOGRAPHY
    </p>
    <ul class="list-inline">
    </ul>
</footer>
<script>
    var check = function () {
        if (document.getElementById('password').value ==
            document.getElementById('confirm_password').value) {
            document.getElementById('message').style.color = 'green';
            document.getElementById('message').innerHTML = 'Matching';
        } else {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = 'Not matching';
        }
    }
</script>

</html>