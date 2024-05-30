<?php
session_start();

//redirects you to login page if you're not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

include 'DBConnector.php';

$user_id = $_SESSION['user_id'];

// Fetch owned books for the user
$sql = "SELECT b.Book_ID, b.Title, b.`Author/s`, b.Thumbnail, b.book_file FROM book b JOIN buy bu ON b.Book_ID = bu.book_ID WHERE bu.user_ID = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owned Books</title>
    <link rel="icon" type="image/png" href="LibraRead.png">
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
            color:white;
        }

        .navbar {
            overflow: hidden;
            background-color: #333;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
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
        .content {
            padding: 20px;
        }
        .book-list {
            list-style-type: none;
            padding: 0;
        }
        .book-item {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .book-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
            border-radius: 5px;
        }
        .buttons {
            margin-left: auto;
        }
        .buttons form {
            display: inline;
        }
        .buttons form button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        .report-button {
            background-color: #f44336;
            color: white;
        }
        .report-button:hover {
            background-color: #d32f2f;
        }
        .read-button {
            background-color: #4CAF50;
            color: white;
        }
        .read-button:hover {
            background-color: #45a049;
        }
        .book-item img {
            width: 100px;
            height: 150px;
            margin-right: 15px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="library.php">Home</a>
        <a href="borrowed.php">Borrowed</a>
        <a class="active" href="owned.php">Owned</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="content">
        <h1>Owned Books</h1>
        <ul class="book-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    //display owned book results
                    echo '<li class="book-item">';
                        echo '<img src="' . $row["Thumbnail"] . '" alt="Book Thumbnail">';
                        echo '<div>';
                            echo '<strong>' . $row["Title"] . '</strong> by ' . $row["Author/s"];
                            
                            echo '<br>';
                            echo '<br>';
                            echo '<br>';

                            echo '<div class="buttons">';
                                echo '<form action="report.php" method="GET">';
                                    echo '<input type="hidden" name="book_id" value="' . $row["Book_ID"] . '">';
                                    echo '<button type="submit" class="report-button">Report</button>';
                                echo '</form>';
                                
                                echo '<form action="view_book.php" method="GET">';
                                    echo '<input type="hidden" name="book_id" value="' . $row["Book_ID"] . '">';
                                    echo '<button type="submit" class="read-button">Read</button>';
                                echo '</form>';
                            echo '</div>';
                        echo '</div>';
                    echo '</li>';
                }
            } else {
                echo "You haven't bought any books.";
            }
            ?>
        </ul>
    </div>
</body>
</html>

<?php
$conn->close();
?>
