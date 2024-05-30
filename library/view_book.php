<?php
include 'DBConnector.php';

// Check if book_id is provided in the URL
if(isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    
    // Fetch the book file path from the database based on the book_id
    $sql = "SELECT book_file FROM book WHERE Book_ID = '$book_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $book_file = $row['book_file'];
        
        // Check if the file exists
        if(file_exists($book_file)) {
            // Output the content of the book file
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>View Book</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 800px;
                        margin: 20px auto;
                        padding: 20px;
                    }
                    .book-content {
                        width: 100%;
                        height: 700px;
                        overflow: hidden;
                        position: relative;
                    }
                    .book-content iframe {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        border: none;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Book Contents</h2>
                    <div class="book-content">
                        <iframe src="<?php echo $book_file; ?>"></iframe>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Book file not found!";
        }
    } else {
        echo "Book not found!";
    }
} else {
    echo "Book ID not provided!";
}

// Close DB connection
$conn->close();
?>
