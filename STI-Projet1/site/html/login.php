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
    <title>PHP Test</title>

    <!-- css (href ne fonctionne pas quand ej fais un dossier /css/fichiers.css)-->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>



<body>

<?php

    if (isset($_POST['sign_in_button']) && !empty($_POST['username'])
        && !empty($_POST['password'])){

        $username = $_POST['username'];
        $password = $_POST['password'];


        //We make sure the username chosen is uniq
        $check_username = $file_db->query("SELECT username FROM users WHERE username='{$username}'")->fetch()[0];
        echo $check_username;

        if(!empty($check_username)){
            $check_password = $file_db->query("SELECT password FROM users WHERE username='{$check_username}'")->fetch()[0];
            if($password == $check_password){
                header("Location:message.php");
                $_SESSION['username'] = $username;
                exit();
            }else{
                header("Location:login.php?error=Wrong password");
                exit();
            }
        }
    }
?>

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