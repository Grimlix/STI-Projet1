<html>
<head></head>
<body>

<?php

  // Set default timezone
  date_default_timezone_set('UTC');

  try {
    /**************************************
    * Create databases and                *
    * open connections                    *
    **************************************/

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);

    /**************************************
    * Create tables                       *
    **************************************/

    // Create table messages
    $messagesTable = "CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    dateOfReceipt DATE,
                    sender TEXT ,
                    receiver TEXT,
                    subject TEXT, 
                    message TEXT, 
                    time TEXT,
                    FOREIGN KEY(sender) REFERENCES users(id),
                    FOREIGN KEY(receiver) REFERENCES users(id))";

    $file_db->exec($messagesTable);

    $usersTable = "CREATE TABLE IF NOT EXISTS users (
       id INTEGER PRIMARY KEY AUTOINCREMENT, 
       username TEXT,
       password TEXT,
       role INTEGER,
       validity INTEGER)";

    $file_db->exec($usersTable);


  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>

</body>
</html>
