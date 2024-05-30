<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing</title>
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

        img {
            width: 200px;
            height: 300px;
        }
        
        button {
            margin-left: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 5px;
            cursor: pointer;
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

    //first check if the user has already borrowed or bought the book
    $borrow_check_query = "SELECT * FROM borrow WHERE user_ID = '$user_id' AND book_ID = '$book_id'";
    $borrow_result = $conn->query($borrow_check_query);

    $buy_check_query = "SELECT * FROM buy WHERE user_ID = '$user_id' AND book_ID = '$book_id'";
    $buy_result = $conn->query($buy_check_query);

    if ($borrow_result->num_rows > 0 || $buy_result->num_rows > 0) {
        //if the user has already borrowed or bought the book
        echo "<div class='borrowed-book-message'>";
        echo "You have already borrowed or bought this book.";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo '<a href="library.php"><button type="button">Return to Menu</button></a>';
        echo "</div>";
    } else {
        //if the user hasn't borrowed or bought the book, proceed with borrowing and insert into the borrow table
        $sql = "INSERT INTO borrow (book_ID, user_ID) VALUES ('$book_id', '$user_id')";

        if ($conn->query($sql) === TRUE) {
            //increment user books_borrowed attribute
            $update_user_query = "UPDATE Users SET books_borrowed = books_borrowed + 1 WHERE user_ID = '$user_id'";

            if ($conn->query($update_user_query) === TRUE) {
                // Fetch book details for display
                $book_query = "SELECT * FROM book WHERE Book_ID = '$book_id'";
                $book_result = $conn->query($book_query);
                if ($book_result->num_rows > 0) {
                    $book_row = $book_result->fetch_assoc();
                    echo "<div class='borrowed-book-container'>";
                    echo '<img src="' . htmlspecialchars($book_row["Thumbnail"]) . '" alt="Book Cover" class="book-cover">';
                    echo "<p class='thank-you-message'>THANK YOU FOR BORROWING THE BOOK</p>";
                    // Calculate due date (i.e. 14 days from now)
                    $due_date = date('Y-m-d', strtotime("+14 days"));
                    echo "<p class='due-date'>Due Date: $due_date</p>";
                    echo '<form action="library.php" method="get">';
                    echo '<button type="submit" class="return-button">Return to Library</button>';
                    echo '</form>';
                    echo "</div>";
                } else {
                    echo "<div class='borrowed-book-message'>";
                    echo "Error: Book details not found.";
                    echo "</div>";
                }
            } else {
                echo "<div class='borrowed-book-message'>";
                echo "Error updating user borrow count: " . $conn->error;
                echo "</div>";
            }
        } else {
            echo "<div class='borrowed-book-message'>";
            echo "Error: " . $sql . "<br>" . $conn->error;
            echo "</div>";
        }
    }
}
$conn->close();
?>
