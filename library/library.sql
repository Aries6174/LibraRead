SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Creating the book table
CREATE TABLE `book` (
  `Title` VARCHAR(100) NOT NULL,
  `Book_ID` INT(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Author/s` VARCHAR(45) NOT NULL,
  `Genre` VARCHAR(45) NOT NULL,
  `Publisher` VARCHAR(45) NOT NULL,
  `year` INT(4) NOT NULL,
  `Description` VARCHAR(10000) NOT NULL,
  `Rating` INT(1) NULL,
  `Price` INT(100) NULL,
  `book_file` VARCHAR(100) NOT NULL,
  `Thumbnail` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`Book_ID`),
  UNIQUE INDEX `Book_ID_UNIQUE` (`Book_ID` ASC)
);

-- Inserting book records
INSERT INTO `book` 
(`Title`, `Author/s`, `Genre`, `Publisher`, `year`, `Description`, `Rating`, `Price`, `book_file`, `Thumbnail`) VALUES
('1984', 'George Orwell', 'Dystopian', 'Secker & Warburg', 1949, 'A dystopian social science fiction novel and cautionary tale about the future.', 5, 20, 'ebooks/1984.pdf', 'ebooks/1984.jpg'),
('Fahrenheit 451', 'Ray Bradbury', 'Science Fiction', 'Ballantine Books', 1953, 'A dystopian novel about a future American society where books are outlawed and "firemen" burn any that are found.', 5, 15, 'ebooks/Fahrenheit 451.pdf', 'ebooks/Fahrenheit 451.jpg'),
('Harry Potter and The Sorcerer\'s Stone', 'J.K. Rowling', 'Fantasy', 'Bloomsbury', 1997, 'The first book in the Harry Potter series, introducing Harry Potter and his adventures at Hogwarts.', 5, 22, 'ebooks/Harry Potter and The Sorcerer\'s Stone.pdf', 'ebooks/Harry Potter and The Sorcerer\'s Stone.jpg'),
('Mein Kampf', 'Adolf Hitler', 'Political', 'Eher Verlag', 1925, 'A manifesto by the Nazi leader Adolf Hitler, outlining his political ideology and future plans for Germany.', 1, 25, 'ebooks/Mein Kampf.pdf', 'ebooks/Mein Kampf.jpg'),
('Pride and Prejudice', 'Jane Austen', 'Romance', 'T. Egerton', 1813, 'A romantic novel that charts the emotional development of the protagonist Elizabeth Bennet.', 5, 10, 'ebooks/Pride and Prejudice.pdf', 'ebooks/Pride and Prejudice.jpg'),
('The Catcher in the Rye', 'J.D. Salinger', 'Coming-of-Age', 'Little, Brown and Company', 1951, 'A story about adolescent alienation and loss of innocence in the protagonist Holden Caulfield.', 4, 12, 'ebooks/The Catcher in the Rye.pdf', 'ebooks/The Catcher in the Rye.jpg'),
('The Diary of a Young Girl', 'Anne Frank', 'Biography', 'Contact Publishing', 1947, 'The writings from the Dutch language diary kept by Anne Frank while she was in hiding.', 5, 15, 'ebooks/The Diary of a Young Girl.pdf', 'ebooks/The Diary of a Young Girl.jpg'),
('The Great Gatsby', 'F. Scott Fitzgerald', 'Classic', 'Charles Scribner\'s Sons', 1925, 'A novel that critiques the disillusionment and moral decay of society in the 1920s.', 5, 18, 'ebooks/The Great Gatsby.pdf', 'ebooks/The Great Gatsby.jpg'),
('The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 'George Allen & Unwin', 1937, 'A children\'s fantasy novel that follows the quest of home-loving Bilbo Baggins to win a share of the treasure guarded by Smaug the dragon.', 5, 18, 'ebooks/The Hobbit.pdf', 'ebooks/The Hobbit.jpg'),
('The Lord of the Rings', 'J.R.R. Tolkien', 'Fantasy', 'George Allen & Unwin', 1954, 'An epic high-fantasy novel set in the world of Middle-earth.', 5, 30, 'ebooks/The Lord of the Rings.pdf', 'ebooks/The Lord of the Rings.jpg'),
('To Kill a Mockingbird', 'Harper Lee', 'Fiction', 'J.B. Lippincott & Co.', 1960, 'A novel about the serious issues of rape and racial inequality.', 5, 15, 'ebooks/To Kill a Mockingbird.pdf', 'ebooks/To Kill a Mockingbird.jpg'),
('War and Peace', 'Leo Tolstoy', 'Historical', 'The Russian Messenger', 1869, 'A novel that chronicles the history of the French invasion of Russia.', 5, 25, 'ebooks/War and Peace.pdf', 'ebooks/War and Peace.jpg');

-- Creating the library table
CREATE TABLE `library` (
  `no_of_books` INT NOT NULL,
  `library_name` VARCHAR(45) NOT NULL,
  `library_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`library_ID`),
  UNIQUE INDEX `library_ID_UNIQUE` (`library_ID` ASC)
);

-- Inserting the library record
INSERT INTO `library` (`no_of_books`, `library_name`)
SELECT COUNT(*), 'LibraRead' FROM `book`;

-- Creating the Users table
CREATE TABLE IF NOT EXISTS Users (
    user_ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    books_owned INT DEFAULT 0,
    books_borrowed INT DEFAULT 0,
    books_returned INT DEFAULT 0
);

-- Creating the borrow table with book_ID and user_ID columns
CREATE TABLE `borrow` (
  `book_ID` INT(7) UNSIGNED NOT NULL,
  `user_ID` INT UNSIGNED NOT NULL,
  `due_date` TIMESTAMP(6) NOT NULL,
  `borrow_date` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  FOREIGN KEY (`book_ID`) REFERENCES `book`(`Book_ID`),
  FOREIGN KEY (`user_ID`) REFERENCES `Users`(`user_ID`)
);

-- Create the trigger for borrow table
DELIMITER $$

CREATE TRIGGER set_due_date
BEFORE INSERT ON `borrow`
FOR EACH ROW
BEGIN
  SET NEW.due_date = NEW.borrow_date + INTERVAL 1 MONTH;
END$$

DELIMITER ;

-- Creating the returns table with book_ID and user_ID columns
CREATE TABLE `returns` (
  `book_ID` INT(7) UNSIGNED NOT NULL,
  `user_ID` INT UNSIGNED NOT NULL,
  `return_date` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  FOREIGN KEY (`book_ID`) REFERENCES `book`(`Book_ID`),
  FOREIGN KEY (`user_ID`) REFERENCES `Users`(`user_ID`)
);

-- Creating the buy table
CREATE TABLE buy (
    buy_ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_ID INT UNSIGNED,
    book_ID INT UNSIGNED,
    buy_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_ID) REFERENCES Users(user_ID),
    FOREIGN KEY (book_ID) REFERENCES book(Book_ID)
);

-- Creating the report table
CREATE TABLE report (
    report_ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(1000) NOT NULL,
    report_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_ID INT UNSIGNED,
    book_ID INT UNSIGNED,
    FOREIGN KEY (user_ID) REFERENCES Users(user_ID),
    FOREIGN KEY (book_ID) REFERENCES book(Book_ID)
);

ALTER TABLE report MODIFY COLUMN description VARCHAR(1000) NOT NULL;
ALTER TABLE report MODIFY COLUMN report_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

COMMIT;
