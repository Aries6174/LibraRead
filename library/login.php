<?php
include 'DBConnector.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    //check email
    $sql = "SELECT * FROM Users WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        //email exists, check password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_ID'];
            $_SESSION['user_name'] = $user['name'];
            //echo "Login successful! Welcome, " . $_SESSION['user_name'];
            // Redirect to main page
            header("Location: library.php");
            exit(); 
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "<h1>No user found with that email.</h1>";
    }
}

$conn->close();
?>
<!-- old ver
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="icon" type="image/png" href="LibraRead.png">
        <style>
            html,body{
                text-align: center;
                font-family:Arial;
                width: 100%;
                height: 100%;
                margin: 0px;
                padding: 0px;
                overflow-x: hidden; 
            }

            body {
                font-family: Arial, sans-serif;
                background-color: #24292e;
                color:white;
            }

            .content {
                padding: 20px;
            }
            .form-container {
                max-width: 400px;
                margin: 0 auto;
                background-color: #333;
                padding: 20px;
                border-radius: 5px;
            }
            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: none;
                border-radius: 3px;
            }
            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 3px;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #45a049;
            }
            button {
                background-color: #f44336;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 3px;
                cursor: pointer;
                border-radius: 5px;
            }
            button:hover {
                background-color: #d32f2f;
            }

            h1{
                padding-top:50px;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <div class="form-container">
                <h2>Login</h2>
                <form action="login.php" method="POST">
                    <input type="text" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Login">
                    <br>
                    <br>
                    <button onclick="location.href='register.php';">Return</button>
                </form>
            </div>
        </div>
    </body>
    </html> -->
