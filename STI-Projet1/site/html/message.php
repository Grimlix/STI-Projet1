<?php

// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="*utf-8">
    <title>Message</title>
    <!-- css (href ne fonctionne pas quand ej fais un dossier /css/fichiers.css)-->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php


    if (isset($_POST['submit_button_message']) && !empty($_POST['message'])
        && !empty($_POST['subject'])){

        $subject = $_POST['subject'];
        $to = $_POST['to'];
        $message = $_POST['message'];

        $create_message = "INSERT INTO messages (sender, receiver, subject, message, dateOfReceipt)
                                VALUES ('{$username}', '{$to}', '{$subject}', 1)";

        $file_db->exec($create_message);
    }


?>


    <div id="container" >
        <h1>Sending message</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
         <ul style="list-style-type: none;">

             <!-- To -->
          <li>
          <label for="to">To</label> </br>
           <input type="text" name="to" id="to" />
          </li>

         <!-- Subject -->
         <li>
             <label for="subject">Subject</label> </br>
             <input type="text" name="subject" id="subject" />
         </li>

             <!-- Message Text -->
          <li>
           <label for="Message">Message</label> </br>
           <textarea name="message" id="message" cols="45" rows="15"></textarea>
          </li>

             <!-- Submit -->
         <li><input type="submit" name="submit_button_message" id="sendMessage" value="Send Message" /></li>


        </ul>
       </form>
    </div>

</body>
</html>
