<?php
session_start();
// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

$stmt = $file_db->prepare("SELECT validity FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$validity = $stmt->fetch()[0];

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

    //quand on clique sur "details"
    if (isset($_POST['details_button'])){
        $id = $_POST['messageId'];

        $stmt = $file_db->prepare("SELECT sender FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $sender = $stmt->fetch()[0];

        $stmt = $file_db->prepare("SELECT subject FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $subject = $stmt->fetch()[0];

        $stmt = $file_db->prepare("SELECT message FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $message = $stmt->fetch()[0];

        $stmt = $file_db->prepare("SELECT dateOfReceipt FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $dateOfReceipt = $stmt->fetch()[0];

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
