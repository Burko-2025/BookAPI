<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require 'connect.php';
  header('Content-Type: application.json');

  //parse form data
  $bookID = isset($_POST['bookID']) ? (int) $_POST['bookID']: 0;
  $title = mysqli_real_escape_string($con, $_POST['title'] ?? '');
  $author = mysqli_real_escape_string($con, $_POST['author'] ?? '');
  $publisher = mysqli_real_escape_string($con, $_POST['publisher'] ?? '');
  $pages = mysqli_real_escape_string($con, $_POST['pages'] ?? '');

  // validation
  if ($bookID < 1 || $title == '' || $lastName == '' || $author = '' 
    || $pages == ''){
        http_response_code (400);
        echo json_encode(['error'=> 'Missing required fields']);
        exit; 
    }

    // check if email already exists excluding current contact
    $checkBookSql = "SELECT bookID FROM books WHERE title = '{$title}' 
        AND contactID != $contactID LIMIT 1 AND author = '{$author}'" ;
    $checkBookResult = mysqli_query($con, $checkBookSql);
    if (mysqli_num_rows($checkBookResult) > 0) {
      http_response_code(409);
      echo json_encode(['error' => 'Book address already exists.']);
      exit;
    }

    //update contact
?>