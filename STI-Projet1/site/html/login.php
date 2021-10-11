<?php

session_destroy();
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
    <title>PHP Test</title>

    <!-- css (href ne fonctionne pas quand ej fais un dossier /css/fichiers.css)-->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php
    if (isset($_POST['button_log_out'])){
        //session_destroy() ne detruit pas les variables associées à la session
        $_SESSION = array();
        session_destroy();
        session_start();
    }
    //si le user est deja log on le redirige sur mailbox.php
    if ($_SESSION['loggedIn']){
        header('Location:mailbox.php?error=Already logged in');
        exit();
    }

    if (isset($_POST['sign_in_button']) && !empty($_POST['username'])
        && !empty($_POST['password'])){

        $username = $_POST['username'];
        $password = $_POST['password'];


        //We make sure the username chosen is uniq
        $check_username = $file_db->query("SELECT username FROM users WHERE username='{$username}'")->fetch()[0];

        if(!empty($check_username)){
            $query = $file_db->query("SELECT password, roles FROM users WHERE username='{$check_username}'")->fetch();

            $check_password = $query[0];
            $role = $query[1];

         if($password == $check_password){

                $validity = $file_db->query("SELECT validity FROM users WHERE username='{$check_username}'")->fetch()[0];
                if(!$validity){
                    header("Location:login.php?error=Account disable");
                    exit();
                }

                header("Location:mailbox.php");
                $_SESSION['username'] = $username;
                $_SESSION['loggedIn'] = true;
                exit();

            }else{
                header("Location:login.php?error=Wrong password");
                exit();
            }
        }
    }
?>

<!-- Boutons de navigation -->
<div class="nav">
    <form action="sign_up.php" method="post">
        <input type="submit" name="button_log_out" value="Sign up">
    </form>
</div>


<form role="form" method="post" action="login.php">
    <div class="LoginBox">

        <!-- Username -->
        <div class="form-group row">
            <label for="inputUser" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
        </div>

        <!-- Password -->
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
        </div>

        <!-- Login page -->
        <div class="form-group row">
            <div class="offset-sm-2">
                <input type="submit" value="Sign in" name="sign_in_button" class="btn btn-primary float-right"/>
            </div>
        </div>

    </div>
</form>


</body>
</html>
