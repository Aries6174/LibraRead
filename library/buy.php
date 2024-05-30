<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
    <link rel="icon" type="image/png" href="LibraRead.png">
    <style>
        html, body {
            font-family: Arial;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #24292e;
            color: white;
            text-align: center;
        }

        div {
            border: solid 3px whitesmoke;
            padding: 20px;
        }

        button {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        img {
            width: 200px;
            height: 300px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $book_id = $conn->real_escape_string($_POST['book_id']);

    // Check if the user exists
    $user_check_query = "SELECT user_ID FROM Users WHERE user_ID = '$user_id'";
    $user_result = $conn->query($user_check_query);

    // Check if the book exists
    $book_check_query = "SELECT Book_ID, Thumbnail FROM book WHERE Book_ID = '$book_id'";
    $book_result = $conn->query($book_check_query);

    // Check if the user has already bought the book
    $already_bought_query = "SELECT * FROM buy WHERE user_ID = '$user_id' AND book_ID = '$book_id'";
    $already_bought_result = $conn->query($already_bought_query);

    if ($user_result->num_rows > 0 && $book_result->num_rows > 0) {
        $book_row = $book_result->fetch_assoc();
        if ($already_bought_result->num_rows == 0) {
            // Check if the user has borrowed the book
            $borrow_check_query = "SELECT * FROM borrow WHERE user_ID = '$user_id' AND book_ID = '$book_id'";
            $borrow_result = $conn->query($borrow_check_query);

            if ($borrow_result->num_rows > 0) {
                // Remove the book from the borrow table
                $delete_borrow_query = "DELETE FROM borrow WHERE user_ID = '$user_id' AND book_ID = '$book_id'";
                if ($conn->query($delete_borrow_query) === TRUE) {
                } else {
                    echo "<div>";
                    echo "Error: " . $delete_borrow_query . "<br>" . $conn->error;
                    echo "</div>";
                }
            }

            // Insert into the buy table
            $insert_buy_query = "INSERT INTO buy (user_ID, book_ID) VALUES ('$user_id', '$book_id')";
            if ($conn->query($insert_buy_query) === TRUE) {
                // Increment the books_owned attribute for the user
                $update_user_query = "UPDATE Users SET books_owned = books_owned + 1 WHERE user_ID = '$user_id'";
                if ($conn->query($update_user_query) === TRUE) {
                    echo "<div>";
                    echo "<img src='" . $book_row['Thumbnail'] . "' alt='Book Cover'>";
                    echo "<p>Book purchased successfully.</p>";
                    echo "<a href='library.php'><button type='button'>Return to Library</button></a>";
                    echo "</div>";
                } else {
                    echo "<div>";
                    echo "Error updating user owned count: " . $conn->error;
                    echo "</div>";
                }
            } else {
                echo "<div>";
                echo "Error: " . $insert_buy_query . "<br>" . $conn->error;
                echo "</div>";
            }
        } else {
            echo "<div>";
            echo "<img src='" . $book_row['Thumbnail'] . "' alt='Book Cover'>";
            echo "<p>You have already purchased this book.</p>";
            echo "<a href='library.php'><button type='button'>Return to Library</button></a>";
            echo "</div>";
        }
    } else {
        echo "<div>";
        echo "User or book does not exist.";
        echo "</div>";
    }
}

$conn->close();
?>
</body>
</html>
