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
    header("Location:login.php?error=Validity is disable");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="*utf-8">
    <title>Password</title>
    <!-- css (href ne fonctionne pas quand ej fais un dossier /css/fichiers.css)-->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php

if(isset($_POST['submit_button']) && !empty($_POST['password1'])){

    $password1 = $_POST['password1'];
    $username = $_SESSION['username'];
    $change_password = "UPDATE users SET password = '{$password1}' WHERE username = '{$username}'";
    $file_db->exec($change_password);
    header("Location:mailbox.php");
    exit();
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

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1>Change Password</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <input type="password" class="input-lg form-control" name="password1" id="password1" autocomplete="off">
                <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" name="submit_button" value="Change Password">
            </form>
        </div>
    </div>
</div>

</body>
</html>
