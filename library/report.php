<?php
session_start();

//redirects you to login page if you're not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

include 'DBConnector.php';

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch book details
    $book_sql = "SELECT Title FROM book WHERE Book_ID = '$book_id'";
    $book_result = $conn->query($book_sql);
    $book = $book_result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = intval($_POST['book_id']);
    $user_id = $_SESSION['user_id'];
    $issue = $conn->real_escape_string($_POST['issue']);
    $description = $conn->real_escape_string($_POST['description']);

    // Insert the report into the database
    $report_sql = "INSERT INTO report (description, user_ID, book_ID) VALUES ('$description', '$user_id', '$book_id')";
    if ($conn->query($report_sql) === TRUE) {
        // Redirect to borrowed.php after successful submission
        header("Location: borrowed.php");
        exit();
    } else {
        echo "Error: " . $report_sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Issue</title>
    <link rel="icon" type="image/png" href="LibraRead.png">
    <!-- <link rel="stylesheet" type="text/css" href="LibraRead.css"> -->
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
        h2 {
            text-align: center !important;
            margin-bottom: 20px;
            margin-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #161b22;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        select, textarea {
            width: 93.5%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .return-btn {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }
        .return-btn:hover {
            background-color: #d32f2f;
        }

        textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

    </style>
</head>
<body>
    <h2>Report an Issue with <?php echo htmlspecialchars($book['Title']); ?></h2>
    <div class="container">
        <form action="report.php" method="POST">
            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
            
            <!-- the different possible issues -->
            <label for="issue">Issue:</label>
            <select name="issue" id="issue" required>
                <option value="Book Not Displaying">Book Not Displaying</option>
                <option value="Missing Pages">Missing Pages</option>
                <option value="Wrong Book">Wrong Book</option>
                <option value="Not Original Work">Not Original Work</option>
                <option value="Inappropriate">Inappropriate</option>
                <option value="Promotes Hate Speech">Promotes Hate Speech</option>
                <option value="Other">Other</option>
            </select>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            
            <input type="submit" value="Submit Report">
        </form>
        <form action="library.php">
            <input type="submit" value="Return" class="return-btn">
        </form>
    </div>
</body>
</html>
