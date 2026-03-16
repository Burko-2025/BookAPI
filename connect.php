<?php

  // ================= DATABASE CONFIGURATION =================
  // Define database connection constants
  // These store the credentials needed to connect to MySQL

  define('DB_HOST', 'localhost'); // Database server location
  define('DB_USER', 'root');      // MySQL username
  define('DB_PASS', '');          // MySQL password
  define('DB_NAME', 'Books');     // Database name


  // ================= DATABASE CONNECTION FUNCTION =================
  // Function that creates and returns a database connection
  function connect() {

    // Create a new MySQLi connection using the defined credentials
    $connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // ================= CONNECTION CHECK =================
    // Verify that the connection was successful
    if (mysqli_connect_errno()) {

      // Stop execution and display error if connection fails
      die("Connection failed: " . mysqli_connect_error());
    }

    // ================= CHARACTER SET =================
    // Set the connection character set to UTF-8
    // This ensures proper handling of special characters
    mysqli_set_charset($connect, "utf8");

    // Return the database connection
    return $connect;
  }


  // ================= CREATE CONNECTION =================
  // Call the connect() function and store the connection in $con
  // This variable will be used in other PHP API files
  $con = connect();

?>