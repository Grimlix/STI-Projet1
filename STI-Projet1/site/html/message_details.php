<?php
session_start();
// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

$query = $file_db->query("SELECT validity FROM users WHERE username='{$_SESSION['username']}'")->fetch();
$validity = $query[0];
if(!$validity){
    header("Location:login.php?error=Account disable");
    $_SESSION = array();
    exit();
}

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

    if(!$_SESSION['loggedIn']){
        header("Location:login.php?error=Access without logging in");
        exit();
    }
    //quand on clique sur "answer"
    if (isset($_POST['details_button'])){
        $id = $_POST['messageId'];
        $sender = $file_db->query("SELECT sender FROM messages WHERE id='{$id}'")->fetch()[0];
        $subject = $file_db->query("SELECT subject FROM messages WHERE id='{$id}'")->fetch()[0];
        $message = $file_db->query("SELECT message FROM messages WHERE id='{$id}'")->fetch()[0];
        $dateOfReceipt = $file_db->query("SELECT dateOfReceipt FROM messages WHERE id='{$id}'")->fetch()[0];
    }

?>

<!-- Boutons de navigation -->
<div class="nav">
    <form action="login.php" method="post">
        <input type="submit" name="button_log_out" value="Log out">
    </form>
    <form action="mailbox.php" method="post">
        <input type="submit" name="button_new_message" value="Home">
    </form>
</div>

<div id="container" >
    <h1>Message details</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <ul style="list-style-type: none;">

            <!-- To -->
            <li>
                <label for="sender"><b>Sender</b></label> </br>
                <span><?php echo $sender; ?></span>
            </li>

            <!-- Subject -->
            <li>
                <label for="subject"><b>Subject</b></label> </br>
                <span><?php echo $subject; ?></span>
            </li>

            <!-- Date -->
            <li>
                <label for="date"><b>Date</b></label> </br>
                <span><?php echo $dateOfReceipt; ?></span>
            </li>

            <!-- Message Text -->
            <li>
                <label for="message"><b>Message</b></label> </br>
                <span><?php echo $message; ?></span>
            </li>


        </ul>
    </form>
</div>
