<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection file
require 'connect.php';

// Tell the client that the response will be JSON
header('Content-Type: application/json');

// Get the raw POST data sent from the Angular frontend
$postdata = file_get_contents("php://input");

// Check if POST data exists and is not empty
if (isset($postdata) && !empty($postdata)) {

  // Convert JSON data into a PHP object
  $request = json_decode($postdata);

  // ================= VALIDATION =================
  // Ensure required fields are not empty
  if (
    trim($request->data->title) === '' ||
    trim($request->data->pages) === '' ||
    trim($request->data->author) === '' ||
    trim($request->data->publisher) === ''

  ) {

    // Send HTTP status code 400 (Bad Request)
    http_response_code(400);

    // Return error message as JSON
    echo json_encode(['message' => 'missing required fields.']);
    exit;
  }

  // ================= SANITIZATION =================
  // Prevent SQL injection by escaping special characters
  $title = mysqli_real_escape_string($con, trim($request->data->title));
  $pages = mysqli_real_escape_string($con, trim($request->data->pages));
  $author = mysqli_real_escape_string($con, trim($request->data->author));
  $publisher = mysqli_real_escape_string($con, trim($request->data->publisher));
  $coverImage = mysqli_real_escape_string($con, trim($request->data->coverImage));

    //extract the image name from the file path
    $originalImageName = str_replace('\\', '/', $coverImage);
    $new = basename($originalImageName);
    if (empty($new)) {
      $new = 'placeholder_100.jpg';
    }

    // allowed image formats
    $allowedExt = ['png', 'jpg', 'jpeg', 'gif'];
    $ext = strtolower(pathinfo($new, PATHINFO_EXTENSION));
    if ($new != 'placeholder_100.jpg' && !in_array($ext, $allowedExt)) {
      http_response_code(400);
      echo json_encode(['message' => 'Invalid image format. Only PNG, JPG, JPEG, and GIF are allowed.']);
      exit;
    }


  // ================= DUPLICATE CHECK =================
  // Check if the book already exists in the database
  $checkBookSql = "SELECT 1 FROM books 
                   WHERE title = '{$title}' 
                   AND author = '{$author}' 
                   AND publisher = '{$publisher}' 
                   LIMIT 1";

  $checkResult = mysqli_query($con, $checkBookSql);

  // If a matching book is found
  if (mysqli_num_rows($checkResult) > 0) {

    // Send HTTP status code 409 (Conflict)
    http_response_code(409);

    // Return duplicate error message
    echo json_encode(['message' => 'Book already exists.']);
    exit;
  }


  // ================= INSERT BOOK =================
  // SQL query to insert the new book into the database
  $sql = "INSERT INTO `books` (`bookID`, `title`, `author`, `publisher`, `pages`, `coverImage`)
          VALUES (NULL, '{$title}', '{$author}', '{$publisher}', '{$pages}', '{$new}')";


  // Execute the SQL query
  if (mysqli_query($con, $sql)) {

    // Send HTTP status code 201 (Created)
    http_response_code(201);

    // Return success response with inserted book information
    echo json_encode([
      'data' => ['bookID' => mysqli_insert_id($con)], // newly created ID
      'title' => $title,
      'author' => $author,
      'publisher' => $publisher,
      'pages' => $pages,
      'coverImage' => $new,
      'message' => 'Book added successfully.'
    ]);

  } else {

    // Send HTTP status code 422 (Unprocessable Entity)
    http_response_code(422);

    // Return failure message
    echo json_encode(['message' => 'Failed to add book.']);
  }
}

?>