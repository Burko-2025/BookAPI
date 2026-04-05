<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require 'connect.php';
error_log("POST:");
error_log(print_r($_POST, true));
error_log("FILES:");
error_log(print_r($_FILES, true));
  header('Content-Type: application/json');

  //parse form data
  $bookID = isset($_POST['bookID']) ? (int) $_POST['bookID']: 0;
  $title = mysqli_real_escape_string($con, $_POST['title'] ?? '');
  $author = mysqli_real_escape_string($con, $_POST['author'] ?? '');
  $publisher = mysqli_real_escape_string($con, $_POST['publisher'] ?? '');
  $pages = mysqli_real_escape_string($con, $_POST['pages'] ?? '');
  $coverImage = ''; // default to empty string if no file uploaded

  // ✅ HANDLE FILE UPLOAD
// Check if a new file was uploaded
  if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] == 0) {

      $targetDir = "uploads/";
      $fileName = time() . '_' . basename($_FILES["coverImage"]["name"]);
      $targetFile = $targetDir . $fileName;

      if (move_uploaded_file($_FILES["coverImage"]["tmp_name"], $targetFile)) {
          $coverImage = $fileName;
      }
  } 
  // If no new file, keep old one
  else {
    // get existing image from form
    $existingImage = $_POST['existingImage'] ?? '';

    if (!empty($existingImage)) {
        // ✅ keep old image
        $coverImage = mysqli_real_escape_string($con, $existingImage);
    } else {
        // ✅ fallback to placeholder
        $coverImage = 'placeholder_100.jpg';
    }
  }


  // validation
  if ($bookID < 1 || $title == '' || $author == '' || $publisher == '' 
    || $pages == ''){
        http_response_code (400);
        echo json_encode(['error'=> 'Missing required fields']);
        exit; 
    }

    // check if book already exists excluding current book
    $checkBookSql = "SELECT bookID FROM books WHERE title = '{$title}' AND author = '{$author}' 
        AND bookID != $bookID LIMIT 1 " ;
    $checkBookResult = mysqli_query($con, $checkBookSql);
    if (mysqli_num_rows($checkBookResult) > 0) {
      http_response_code(409);
      echo json_encode(['error' => 'Book already exists.']);
      exit;
    }

    //update book in database

    $sql = "UPDATE books SET title = '{$title}', author = '{$author}', publisher = '{$publisher}', 
      pages = '{$pages}', coverImage = '{$coverImage}' WHERE bookID = $bookID LIMIT 1";

    if (mysqli_query($con, $sql)) {
        http_response_code(200);
        echo json_encode(['message' => 'Book updated successfully.']);
    }
    else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update book.']);
    }