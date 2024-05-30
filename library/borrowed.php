<?php
session_start();

//redirects you to login page if you're not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

include 'DBConnector.php';

$user_id = $_SESSION['user_id'];

//Book return function
if (isset($_POST['return_book_id'])) {
    $book_id = $_POST['return_book_id'];
    $return_date = date("Y-m-d"); //get current date as return date
    $sql_delete = "DELETE FROM borrow WHERE user_ID = '$user_id' AND book_ID = '$book_id'"; //delete row from borrow table

    //then insert info into returns table
    if ($conn->query($sql_delete) === TRUE) {
        $sql_insert = "INSERT INTO returns (user_ID, book_ID, return_date) VALUES ('$user_id', '$book_id', '$return_date')";
        if ($conn->query($sql_insert) === TRUE) {
            //increment user books_returned attribute
            $update_user_query = "UPDATE Users SET books_returned = books_returned + 1 WHERE user_ID = '$user_id'";
            if ($conn->query($update_user_query) === TRUE) {
            } else {
            }
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

// Fetch borrowed books for the user along with due dates
$sql = "SELECT b.Book_ID, b.Title, b.`Author/s`, b.Thumbnail, DATE_FORMAT(br.Due_Date, '%e %M %Y') AS Due_Date, b.book_file FROM book b JOIN borrow br ON b.Book_ID = br.book_ID WHERE br.user_ID = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
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
            position: relative;
            display: flex;
            align-items: center;
        }
        .book-item:hover .return-btn {
            display: block;
        }
        .return-btn {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .return-btn:hover {
            background-color: #d32f2f;
        }
        .book-thumbnail {
            width: 75px;
            height: 100px;
            margin-right: 15px;
            border-radius: 5px;
        }
        .due-date {
            margin-left: auto;
        }
        .read-btn {
            margin-left: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .read-btn:hover {
            background-color: #45a049;
        }
        .report-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin:25px;
        }
        .report-btn:hover {
            background-color: #d32f2f;
        }

        .book-item img {
            width: 100px;
            height: 150px;
            margin-right: 15px;
            object-fit: cover;
            border-radius: 5px;
        }

        button{
            padding: 15px 20px;
        }

    </style>
</head>
<body>
    <div class="navbar">
        <a href="library.php">Home</a>
        <a class="active" href="borrowed.php">Borrowed</a>
        <a href="owned.php">Owned</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="content">
        <h1>Borrowed Books</h1>
        <ul class="book-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    //display borrowed book results
                    echo '<li class="book-item">';
                    echo '<img src="' . $row["Thumbnail"] . '" alt="Book Thumbnail" class="book-thumbnail">';

                    echo '<div>';
                        echo '<strong>' . $row["Title"] . '</strong> by ' . $row["Author/s"];
                        echo '<div class="buttons">';
                            echo '<form action="borrowed.php" method="POST" style="display:inline;">';
                                echo '<input type="hidden" name="return_book_id" value="' . $row["Book_ID"] . '">';
                                echo '<button type="submit" class="return-btn">Return</button>';
                            echo '</form>';

                            echo '<button class="read-btn" onclick="window.open(\'view_book.php?book_id=' . $row["Book_ID"] . '\', \'_self\')">Read</button>';

                            echo '<form action="report.php" method="GET" style="display:inline;">';
                                echo '<input type="hidden" name="book_id" value="' . $row["Book_ID"] . '">';
                                echo '<button class="report-btn" type="submit">Report</button>';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';

                    echo '<div class="due-date">Due Date: ' . $row["Due_Date"] . '</div>';
                    echo '</li>';
                }
            } else {
                echo "You haven't borrowed any books.";
            }
            ?>
        </ul>
    </div>
</body>
</html>

<?php
$conn->close();
?>
