<?php
session_start();

//redirects you to login page if you're not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

include 'DBConnector.php';

// get user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, books_owned, books_borrowed, books_returned FROM Users WHERE user_ID = '$user_id'";
$result = $conn->query($sql);

// check if previous query was successful
if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    die("Error fetching user details: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        html,body{
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
            color:black;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .navbar a.active {
            background-color: #4CAF50;
            color: white;
        }
        .container {
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            text-align: center;
        }
        p {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="library.php">Home</a>
        <a href="borrowed.php">Borrowed</a>
        <a href="owned.php">Owned</a>
        <a class="active" href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Your Profile</h2>
        <table>
            <!-- <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr> -->
            <tr>
                <th>Name</th>
                <th><?php echo htmlspecialchars($user['name']); ?></th>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <td>Books Owned</td>
                <td><?php echo htmlspecialchars($user['books_owned']); ?></td>
            </tr>
            <tr>
                <td>Books Borrowed</td>
                <td><?php echo htmlspecialchars($user['books_borrowed']); ?></td>
            </tr>
            <tr>
                <td>Books Returned</td>
                <td><?php echo htmlspecialchars($user['books_returned']); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
