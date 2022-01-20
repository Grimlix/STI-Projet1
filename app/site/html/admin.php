<?php
include 'utils.php';
session_start();

// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

if(!$_SESSION['loggedIn']){
    header("Location:login.php?error=Access without logging in");
    exit();
}
$stmt = $file_db->prepare("SELECT roles FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$query = $stmt->fetch();
$role = $query[0];
if(!$role){
    header("Location:mailbox.php?error=Not sufficient permissions");
    exit();
}

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
        $newPassword = $_POST['new_password_text'];
        if(strongPasswordVerify($newPassword)){
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $file_db->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$passwordHash, $_POST['user_password']]);
        }
        else{
            header("Location:admin.php?error=Weak Password");
            exit();
        }
    }else if(isset($_POST['button_delete'])){
        delete_user($_POST['user_delete']);
    }

    function change_validity($username){
        global $file_db;

        $stmt = $file_db->prepare("SELECT validity FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $validity = $stmt->fetch()[0];

        if($validity == 1){
            $stmt = $file_db->prepare("UPDATE users SET validity = 0 WHERE username = ?");
            $stmt->execute([$username]);
        }else{
            $stmt = $file_db->prepare("UPDATE users SET validity = 1 WHERE username = ?");
            $stmt->execute([$username]);
        }
    }

    function change_role($username){
        global $file_db;

        $stmt = $file_db->prepare("SELECT roles FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $role = $stmt->fetch()[0];

        if($role == 0){
            $stmt = $file_db->prepare("UPDATE users SET roles = 1 WHERE username = ?");
            $stmt->execute([$username]);
        }else{
            $stmt = $file_db->prepare("UPDATE users SET roles = 0 WHERE username = ?");
            $stmt->execute([$username]);
        }
    }

    function delete_user($username){
        global $file_db;
        $stmt = $file_db->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);
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
        <div class="col-12">
            <table class="table table-bordered">


                <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Password</th>
                    <th scope="col">Role</th>
                    <th scope="col">Validity</th>
                    <th scope="col">Removal</th>
                </tr>
                </thead>


                <tbody>

                <?php

                $stmt = $file_db->prepare("SELECT * FROM users WHERE NOT roles = ?");
                $stmt->execute([1]);
                $users = $stmt->fetchAll();

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
                        <td>
                            <form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="display: inline">
                                <input type="hidden" name="user_delete" value="<?php echo $user[0] ?>"/>
                                <input type="submit" value="Delete user" name="button_delete" class="btn btn-danger"/>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>


            </table>

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
