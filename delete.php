<?php
    require 'connect.php';

    $bookID = ($_GET['bookID']!=null && (int) $_GET['bookID'] > 0) ? mysqli_real_escape_string($con, (int)$_GET['bookID']) : false;

    if (!$contactID) {
        return http_response_code(400);
    }

    // delete the contact record
    $sql = "DELETE FROM `books` WHERE `bookID` = '{$bookID}' LIMIT 1";

    if (mysqli_query($con, $sql)) {
      http_response_code(204);
      
    }
    else {
      http_response_code(422);
    }


?>