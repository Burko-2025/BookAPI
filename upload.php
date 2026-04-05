<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  header('Content-Type: application/json');

  $uploadDir = 'uploads/';

  //check if file was uploaded
if (!isset($_FILES['image'])) {
  echo json_encode(['error' => 'image not set']);
  exit;
}

if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
  echo json_encode([
    'error' => 'upload error',
    'code' => $_FILES['image']['error']
  ]);
  exit;
}
  //validate file type, only allow PNG, JPG, GIF
  $allowedMimeTypes = [   'image/jpeg',
  'image/png',
  'image/gif',
  'image/jpg',
  'image/pjpeg'];
  $fileTmpPath = $_FILES['image']['tmp_name'];
  $fileName = basename($_FILES['image']['name']);
$ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $allowedExt)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid image format. Only JPG, PNG, GIF allowed.']);
    exit;
}

  //move file to upload folder
  $targetFilePath = $uploadDir . $fileName;
  if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
    http_response_code(200);
    echo json_encode(['message' => 'File uploaded successfully.', 'fileName' => $fileName]);
  } else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to move uploaded file.']);
  }
?>