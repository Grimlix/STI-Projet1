
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
    $dropMessages = "DROP TABLE IF EXISTS messages";
    $dropUsers = "DROP TABLE IF EXISTS users";


    $file_db->exec($dropMessages);
    $file_db->exec($dropUsers);



    // Create table messages
    $messagesTable = "CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    sender TEXT ,
                    receiver TEXT,
                    subject TEXT, 
                    message TEXT,          
                    dateOfReceipt DATETIME,
                    FOREIGN KEY(sender) REFERENCES users(username),
                    FOREIGN KEY(receiver) REFERENCES users(username))";

    $file_db->exec($messagesTable);

    $usersTable = "CREATE TABLE IF NOT EXISTS users (
       username TEXT PRIMARY KEY,
       password TEXT,
       roles BOOLEAN  DEFAULT 0,
       validity BOOLEAN  DEFAULT 1)";

    $file_db->exec($usersTable);

    $mdp = password_hash('A4UY3AUrAEQs9j%', PASSWORD_DEFAULT);
    $insertAdmin = "INSERT INTO users (username, password, roles)
                    VALUES ('admin', '{$mdp}', 1)";

    $file_db->exec($insertAdmin);


  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>

