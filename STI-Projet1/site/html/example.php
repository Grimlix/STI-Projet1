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
    $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY, 
                    title TEXT, 
                    message TEXT, 
                    time TEXT)"); 
 
    /**************************************
    * Set initial data                    *
    **************************************/
 
    // Array with some test data to insert to database             
    $messages = array(
                  array('title' => 'Hello!',
                        'message' => 'Just testing...',
                        'time' => 1327301464),
                  array('title' => 'Hello again!',
                        'message' => 'More testing...',
                        'time' => 1339428612),
                  array('title' => 'Hi!',
                        'message' => 'SQLite3 is cool...',
                        'time' => 1327214268)
                );
 
 
    /**************************************
    * Play with databases and tables      *
    **************************************/
 
    foreach ($messages as $m) {
        $formatted_time = date('Y-m-d H:i:s', $m['time']);
        $file_db->exec("INSERT INTO messages (title, message, time) 
                VALUES ('{$m['title']}', '{$m['message']}', '{$formatted_time}')");
    }
 
    $result =  $file_db->query('SELECT * FROM messages');
 
    foreach($result as $row) {
      echo "Id: " . $row['id'] . "<br/>";
      echo "Title: " . $row['title'] . "<br/>";
      echo "Message: " . $row['message'] . "<br/>";
      echo "Time: " . $row['time'] . "<br/>";
      echo "<br/>";
    }
 
 
    /**************************************
    * Drop tables                         *
    **************************************/
 
    // Drop table messages from file db
    $file_db->exec("DROP TABLE messages"); 
 
    /**************************************
    * Close db connections                *
    **************************************/
 
    // Close file db connection
    $file_db = null;
  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }
?>

<h1> Hello World</h1>
</body>
</html>
