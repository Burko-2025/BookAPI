<?php

// ================= ERROR REPORTING =================
// Enable PHP error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ================= DATABASE CONNECTION =================
// Include the file that connects to the database
require 'connect.php';

// Initialize an empty array to store books
$books = [];

// ================= SQL QUERY =================
// Select all books from the database
$sql = "SELECT bookID, title, author, publisher, pages, coverImage FROM books";

// Execute the SQL query
if ($result = mysqli_query($con, $sql)) {

    // Initialize a counter to keep track of array indices
    $count = 0;

    // Fetch each row as an associative array
    while ($row = mysqli_fetch_assoc($result)) {

        // Store each book's data in the $books array
        $books[$count]['bookID']   = $row['bookID'];
        $books[$count]['title']    = $row['title'];
        $books[$count]['author']   = $row['author'];
        $books[$count]['publisher']= $row['publisher'];
        $books[$count]['pages']    = $row['pages'];
        $books[$count]['coverImage'] = $row['coverImage'];

        // Increment the counter for the next book
        $count++;
    }

    // Encode the books array as JSON and send it to the frontend
    echo json_encode(['data' => $books]);

} else {

    // If the query fails, return HTTP status code 404 (Not Found)
    http_response_code(404);
}

?>