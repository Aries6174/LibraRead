<?php
include 'DBConnector.php';

session_start();

// Insert user data into the Users table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $name = $_POST['name'];
    // $email = $_POST['email'];
    // $password = $_POST['password'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT); //password_verify in login.php requires this to be hashed here first

    $sql_insert_user = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql_insert_user) === TRUE) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $name;
        header("Location: library.php");
        exit();
        //echo "New record created successfully";
    } else {
        echo "Error: " . $sql_insert_user . "<br>" . $conn->error;
    }
}

//echo '<link rel="stylesheet" type="text/css" href="style.css">';
$conn->close();
?>
