<?php
session_start();
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
    $receiverValue = "";

    if(!$_SESSION['loggedIn']){
        header("Location:login.php?error=Access without logging in");
        exit();
    }

    //Quand on appuye sur "send message"
    if (isset($_POST['submit_button_message']) && !empty($_POST['message'])
        && !empty($_POST['subject']) && !empty($_POST['to'])) {

        $subject = $_POST['subject'];
        $to = $_POST['to'];
        $message = $_POST['message'];
        $sender = $_SESSION['username'];

        //Verification du destinataire
        $receiver = $file_db->query("SELECT username FROM users WHERE username='{$to}'")->fetch()[0];

        if (strcmp($receiver, $sender) == 0) {
            header("Location:message.php?error=Wrong receiver");
            exit();
        } else if (!empty($receiver)) {
            echo 'Message sent';

            date_default_timezone_set('Europe/Zurich');
            $date = date('m/d/Y h:i:s a', time());

            $create_message = "INSERT INTO messages (sender, receiver, subject, message, dateOfReceipt)
                                VALUES ('{$sender}', '{$to}', '{$subject}', '{$message}', '{$date}')";

            $file_db->exec($create_message);


            header("Location:messages.php");
            $_SESSION['messageId'] = null;
            exit();
        }

    }
    //quand on clique sur "answer"
    if (isset($_SESSION['messageId'])){
        $id = $_SESSION['messageId'];
        $receiverValue = $file_db->query("SELECT sender FROM messages WHERE id='{$id}'")->fetch()[0];
    }

?>
<!-- Boutons de navigation -->
<div class="nav">
    <form action="login.php" method="post">
        <input type="submit" name="button_log_out" value="Log out">
    </form>
    <form action="messages.php" method="post">
        <input type="submit" name="button_new_message" value="Home">
    </form>
</div>


<div id="container" >
    <h1>Sending message</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
     <ul style="list-style-type: none;">

         <!-- To -->
      <li>
      <label for="to">To</label> </br>
       <input type="text" name="to" id="to" value="<?= $receiverValue ?>"/>
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
