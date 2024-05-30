<?php
session_start();

include 'DBConnector.php';

//check if the user is already logged in, redirect to library if true
if (isset($_SESSION['user_id'])) {
    header("Location: library.php");
    exit();
}

// Initialize error message variable
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM Users WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        //if user already exists
        $error_message = "Email already exists. Please choose a different email or login.";
    } else {
        //if user doesn't exist, proceed with registration
        $name = $conn->real_escape_string($_POST['name']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql_insert = "INSERT INTO Users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        if ($conn->query($sql_insert) === TRUE) {
            // Registration successful, redirect to login page
            header("Location: register.php");
            exit(); 
        } else {
            $error_message = "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="icon" type="image/png" href="LibraRead.png">
    <link rel="stylesheet" type="text/css" href="LibraRead.css">
    <style>
        .header {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #161b22;
            color: #c9d1d9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        .header img {
            height: 40px;
            margin-right: 15px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="LibraRead.png" alt="LibraRead Logo">
        <h1>LibraRead</h1>
    </div>
    <div class="container">
        <!-- Register box -->
        <div class="form-container">
            <h2>Register</h2>
            <span class="error-message"><?php echo $error_message; ?></span>
            <form action="register.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <input type="submit" value="Register">
            </form>
        </div>
        <!-- Login box -->
        <div class="form-container">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>
</html>
