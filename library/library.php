<?php
session_start();

//redirects you to login page if you're not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

include 'DBConnector.php';

//define base SQL query as every book
$sql = "SELECT * FROM book";

//Filter function
//checks if any filter option is selected
if (!empty($_GET['genre']) || !empty($_GET['year']) || !empty($_GET['rating'])) {
    $sql .= " WHERE";

    //genre
    if (!empty($_GET['genre'])) {
        $genre = $_GET['genre'];
        $sql .= " `Genre` = '$genre'";
    }

    //year
    if (!empty($_GET['year'])) {
        $year = $_GET['year'];
        if (!empty($_GET['genre'])) {
            $sql .= " AND";
        }
        $sql .= " `year` = $year";
    }

    //rating
    if (!empty($_GET['rating'])) {
        $rating = $_GET['rating'];
        if (!empty($_GET['genre']) || !empty($_GET['year'])) {
            $sql .= " AND";
        }
        $sql .= " `Rating` = $rating";
    }
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
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
            border: 3px solid #ccc;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .book-item img {
            width: 100px;
            height: 150px;
            margin-right: 15px;
            object-fit: cover;
            border-radius: 5px;
        }
        .actions {
            margin-left: auto;
        }
        .actions button {
            margin-left: 5px;
        }

        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f1f1f1;
            color: #333;
            font-size: 14px;
            margin-right: 10px;
        }

        button{
            margin-left: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .title{
            font-size:20px;
            font-weight: bold;
        }


    </style>
</head>
<body>
    <div class="navbar">
        <a class="active" href="library.php">Home</a>
        <a href="borrowed.php">Borrowed</a>
        <a href="owned.php">Owned</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="content">
        <h1>Welcome to the Library, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <br>
        <br>
        <h2>AVAILABLE BOOKS:</h2>
        <form action="" method="get">
            <!-- Filter section -->
            <label for="genre">Genre:</label>
            <select name="genre" id="genre">
                <option value="">All</option>
                <?php
                //simply fetching distinct genres
                $sql_genres = "SELECT DISTINCT `Genre` FROM book ORDER BY `Genre`";
                $result_genres = $conn->query($sql_genres);
                if ($result_genres->num_rows > 0) {
                    while($row_genre = $result_genres->fetch_assoc()) {
                        echo '<option value="' . $row_genre['Genre'] . '">' . $row_genre['Genre'] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="year">Year:</label>
            <select name="year" id="year">
                <option value="">All</option>
                <?php
                //fetch distinct years
                $sql_years = "SELECT DISTINCT `year` FROM book ORDER BY `year` DESC";
                $result_years = $conn->query($sql_years);
                if ($result_years->num_rows > 0) {
                    while($row_year = $result_years->fetch_assoc()) {
                        echo '<option value="' . $row_year['year'] . '">' . $row_year['year'] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="rating">Rating:</label>
            <select name="rating" id="rating">
                <option value="">All</option>
                <?php
                //fetch distinct ratings
                $sql_ratings = "SELECT DISTINCT `Rating` FROM book ORDER BY `Rating` DESC";
                $result_ratings = $conn->query($sql_ratings);
                if ($result_ratings->num_rows > 0) {
                    while($row_rating = $result_ratings->fetch_assoc()) {
                        echo '<option value="' . $row_rating['Rating'] . '">' . $row_rating['Rating'] . '</option>';
                    }
                }
                ?>
            </select>

            <button type="submit">Filter</button>
        </form>
        <ul class="book-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<li class="book-item">';
                        // Check if the 'Thumbnail' exists and has a valid value
                        if (isset($row['Thumbnail']) && !empty($row['Thumbnail'])) {
                            echo '<img src="' . htmlspecialchars($row["Thumbnail"]) . '" alt="Book Thumbnail">';
                        } else {
                            echo '<img src="default_thumbnail.jpg" alt="Default Thumbnail">';
                        }

                        //display book results
                        echo '<div>';
                            echo '<div class="title"><strong>' . htmlspecialchars($row["Title"]) . '</strong> by ' . htmlspecialchars($row["Author/s"]).'</div>';   
                            echo '<p>' . htmlspecialchars($row["Description"]) . '</p>';
                        echo '</div>';
                            
                        echo '<div class="actions">';
                            echo '<form action="borrow.php" method="post" style="display:inline-block;">';
                                echo '<input type="hidden" name="book_id" value="' . htmlspecialchars($row["Book_ID"]) . '">';
                                echo '<button type="submit">Borrow</button>';
                            echo '</form>';
                            
                            echo '<form action="buy.php" method="post" style="display:inline-block;">';
                                echo '<input type="hidden" name="book_id" value="' . htmlspecialchars($row["Book_ID"]) . '">';
                                echo '<button type="submit">Buy: $' . htmlspecialchars($row["Price"]) . '</button>';
                            echo '</form>';
                        echo '</div>';
                    echo '</li>';
                }
            } else {
                echo "No books available.";
            }

            ?>
        </ul>
    </div>
</body>
</html>

<?php
$conn->close();
?>
