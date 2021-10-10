<?php
session_start();

if(!$_SESSION['loggedIn']){
    header("Location:login.php?error=Access without logging in");
    exit();
}else if(!$_SESSION['admin']){
    header("Location:messages.php?error=Not sufficient permissions");
    exit();
}


// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

unset($_SESSION['messageId']);


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="*utf-8">
    <title>Messages</title>

    <!-- css (href ne fonctionne pas quand ej fais un dossier /css/fichiers.css)-->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php

    if (isset($_POST['button_validity'])){
        change_validity($_POST['user_validity']);
    }else if(isset($_POST['button_role'])){
        change_role($_POST['user_role']);
    }else if(isset($_POST['button_password']) && !empty($_POST['new_password_text'])){
        $change_password = "UPDATE users SET password = '{$_POST['new_password_text']}' WHERE username = '{$_POST['user_password']}'";
        $file_db->exec($change_password);
    }

    function change_validity($username){
        global $file_db;
        $get_validity = "SELECT validity FROM users WHERE username = '{$username}'";
        $validity = $file_db->query($get_validity)->fetch()[0];
        if($validity == 1){
            $validity_update = "UPDATE users SET validity = 0 WHERE username = '{$username}'";
        }else{
            $validity_update = "UPDATE users SET validity = 1 WHERE username = '{$username}'";
        }
        $file_db->exec($validity_update);
    }

    function change_role($username){
        global $file_db;
        $get_role = "SELECT role FROM users WHERE username = '{$username}'";
        $role = $file_db->query($get_role)->fetch()[0];
        if($role == "Collaborator"){
            $role_update = "UPDATE users SET role = 'Administrator' WHERE username = '{$username}'";
        }else{
            $role_update = "UPDATE users SET role = 'Collaborator' WHERE username = '{$username}'";
        }
        $file_db->exec($role_update);
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

<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">


                <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Password</th>
                    <th scope="col">Role</th>
                    <th scope="col">Validity</th>
                </tr>
                </thead>


                <tbody>

                <?php
                $user_connected  = $_SESSION['username'];
                $users = $file_db->query("SELECT * FROM users WHERE NOT username = '{$user_connected}'")->fetchAll();

                foreach($users as $user): ?>
                    <tr>
                        <td><?= $user[0]; ?></td>
                        <td><?= $user[1]; ?>
                            <form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="display: inline">
                                <input type="hidden" name="user_password" value="<?php echo $user[0] ?>"/>
                                <input type="text" class="form-control" name="new_password_text" placeholder="New password">
                                <input type="submit" value="Change password" name="button_password" class="btn btn-primary"/>
                            </form>
                        </td>
                        <td><?= $user[2]; ?>
                            <form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="display: inline">
                                <input type="hidden" name="user_role" value="<?php echo $user[0] ?>"/>
                                <input type="submit" value="Change role" name="button_role" class="btn btn-success"/>
                            </form>
                        </td>
                        <td><?= $user[3]; ?>
                            <form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="display: inline">
                                <input type="hidden" name="user_validity" value="<?php echo $user[0] ?>"/>
                                <input type="submit" value="Change validity" name="button_validity" class="btn btn-danger"/>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>


            </table>

        </div>
    </div>
</div>

</body>
</html>
