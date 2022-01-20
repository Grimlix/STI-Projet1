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

$query = $file_db->query("SELECT roles FROM users WHERE username='{$_SESSION['username']}'")->fetch();
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
    // add user button
    if (isset($_POST['add_user_button'])){
        if (!empty($_POST['username']) && !empty($_POST['password'])){
            $username = htmlentities($_POST['username']);
            $password = htmlentities($_POST['password']);
            $role = $_POST['role'];
            $validity = $_POST['validity'];

            if(strlen($username) > 20){
                header("Location:add_user.php?error=Username too long (max 20)");
                exit();
            }

            //We make sure the username chosen is uniq
            $check_username = $file_db->query("SELECT username FROM users WHERE username='{$username}'")->fetch()[0];
            if(empty($check_username)){
                if(strongPasswordVerify($password)){
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $file_db->exec("INSERT INTO users 
                                         VALUES ('{$username}', '{$passwordHash}', '{$role}', '{$validity}')   ");
                    header("Location:add_user.php");
                    exit();
                }
                else{
                    header("Location:add_user.php?error=Weak Password");
                    exit();
                }
            }

        }
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

<form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
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
                        <th scope="col">Add</th>
                    </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="username" placeholder="username" maxlength="20">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="password" placeholder="password">
                            </td>
                            <td>
                                <select name="role">
                                    <option value="1">Administrator</option>
                                    <option value="0">Collaborator</option>
                                </select>
                            </td>
                            <td>
                                <select name="validity">
                                    <option value="1">Enable</option>
                                    <option value="0">Disable</option>
                                </select>                            </td>
                            <td>
                                <input type="submit" value="Add user" name="add_user_button" class="btn btn-danger"/>
                            </td>
                        </tr>
                    </tbody>

                </table>
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
    </div>
</form>
</body>
</html>
