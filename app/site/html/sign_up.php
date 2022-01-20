<?php
include 'utils.php';

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

    if (isset($_POST['submit_button']) && !empty($_POST['username'])
        && !empty($_POST['password'])){

        $username = htmlentities($_POST['username']);
        $password = htmlentities($_POST['password']);

        if(strlen($username) > 20){
            header("Location:sign_up.php?error=Username too long (max 20)");
            exit();
        }

        //We make sure the username chosen is uniq
        $stmt = $file_db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $check_username = $stmt->fetch();

        if($check_username != false){
            header("Location:sign_up.php?error=Username already used");
            exit();
        }

        if(strongPasswordVerify($password)){
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $file_db->prepare("INSERT INTO users (username, password)
                        VALUES (?, ?)");
            $stmt->execute([$username, $passwordHash]);

            header("Location:login.php");
            exit();
        }
        else{
            header("Location:sign_up.php?error=Weak Password");
            exit();
        }
    }

?>

<!-- Boutons de navigation -->
<div class="nav">
    <form action="login.php" method="post">
        <input type="submit" name="button_log_out" value="Sign in">
    </form>
</div>

<form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
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

     <!-- Submit -->
     <div class="form-group row">
         <div class="offset-sm-2 col-sm-10">
             <input type="submit" value="Create" name="submit_button" class="btn btn-primary"/>
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
</form>


</body>
</html>
