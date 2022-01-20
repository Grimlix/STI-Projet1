<?php
include 'utils.php';

session_start();

// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');

// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

$stmt = $file_db->prepare("SELECT validity FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$query = $stmt->fetch();

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
    <!-- css (href ne fonctionne pas quand je fais un dossier /css/fichiers.css)-->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php

if(isset($_POST['submit_button']) && !empty($_POST['password_changed'] && !empty($_POST['actual_password']))){

    $username = $_SESSION['username'];
    $actualPassword = $_POST['actual_password'];

    $stmt = $file_db->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $query = $stmt->fetch();

    $check_password = $query[0];

    if(password_verify($actualPassword, $check_password)) {
        $password_changed = htmlentities($_POST['password_changed']);

        if(strongPasswordVerify($password_changed)){
            $passwordHash = password_hash($password_changed, PASSWORD_DEFAULT);

            $stmt = $file_db->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$passwordHash, $_SESSION['username']]);

            header("Location:mailbox.php");
            exit();
        }
        else{
            header("Location:change_password.php?error=Weak Password");
            exit();
        }
    }

    echo "Ancien mot de passe faux.";
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
                <p>Ancien mot de passe</p>
                <input type="password" class="input-lg form-control" name="actual_password" id="actual_password" autocomplete="off">
                <p>Nouveau mot de passe</p>
                <input type="password" class="input-lg form-control" name="password_changed" id="password_changed" autocomplete="off">
                <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" name="submit_button" value="Change Password">
            </form>
        </div>
    </div>
    <p>
        <?php if(!empty($_GET['error'])){
            echo nl2br ("Mot de passe faible, le mot de passe doit contenir au moins :\n
             -Une longueur de 15 caracteres\n
             -Une majuscule et minuscule\n
             -Un nombre\n
             -Un caractere special\n
             Le nom d'utilisateur doit etre de 20 caracteres maximum");
        } ?>
    </p>
</div>

</body>
</html>
