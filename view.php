<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require 'connect.php';
  header('Content-Type: application.json');

  
  $bookID = isset($_GET['bookID']) ? (int) $_GET['bookID']: 0;

  if ($bookID < 1) {
      http_response_code(400);
      echo json_encode(['error' => 'Invalid book ID.']);
      exit;
  }

  $sql = "SELECT bookID, title, pages, author, publisher, coverImage 
      FROM books WHERE bookID = {$bookID} LIMIT 1";

   if ($result = mysqli_query($con, $sql)) {
      if (mysqli_num_rows($result) == 1) {
        echo json_encode(mysqli_fetch_assoc($result));
      }
      else {
         http_response_code(404);
         echo json_encode(['error' => 'Book not found.']);
      }
  }
  else {
      http_response_code(500);
      echo json_encode(['error' => 'Failed to retrieve Book.']);}
?>