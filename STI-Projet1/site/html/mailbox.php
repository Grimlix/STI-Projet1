<?php
session_start();
// Create (connect to) SQLite database in file
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

$query = $file_db->query("SELECT roles, validity FROM users WHERE username='{$_SESSION['username']}'")->fetch();
$role = $query[0];
$validity = $query[1];
if(!$validity){
    header("Location:login.php?error=Account disable");
    $_SESSION = array();
    exit();
}

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

    /*
    ->query() : requête qui ne modifie pas la base de données
    ->fetch() : récupérer la donnée de la requête (toujours dans un array)
    ->fetchAll() : récupère tous array[0][]
    ->exec() : requête qui modifie la base de données

    */

    if(!$_SESSION['loggedIn']){
        header("Location:login.php?error=Access without logging in");
        exit();
    }


// delete button
    if (isset($_POST['delete_button'])){
        $id = $_POST['messageId'];
        $delete_message = "DELETE FROM messages WHERE id ='{$id}'";
        $file_db->exec($delete_message);
    }

?>
<!-- Boutons de navigation -->
<div class="nav">
    <form action="login.php" method="post">
        <input type="submit" name="button_log_out" value="Log out">
    </form>
    <form action="change_password.php" method="post">
        <input type="submit" name="button_change_password" value="Change password">
    </form>
    <form action="new_message.php" method="post">
        <input type="submit" name="button_new_message" value="New message">
    </form>
    <form <?php if (!$role){ ?> style="display:none"<?php } ?> action="admin.php" method="post">
        <input type="submit" name="button_admin" value="Admin">
    </form>
    <form <?php if (!$role){ ?> style="display:none"<?php } ?> action="add_user.php" method="post">
        <input type="submit" name="button_new_message" value="Add user">
    </form>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">


                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Sender</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>


                <tbody>

                <?php
                $receiver  = $_SESSION['username'];

                $messages = $file_db->query("SELECT * FROM messages WHERE receiver='{$receiver}'")->fetchAll();
                //[0][1] -> sender
                foreach($messages as $message): ?>
                    <tr>
                        <td><?= $message[5]; ?></td>
                        <td><?= $message[3]; ?></td>
                        <td><?= $message[1]; ?></td>
                        <td>
                            <div class="container">
                                <form role="form" method="post" action="message_details.php" style="display: inline">
                                    <input type="hidden" id="messageId" name="messageId" value="<?php echo $message[0]; ?>"/>
                                    <input type="submit" value="Details" name="details_button" class="btn btn-primary"/>
                                </form>
                                <form role="form" method="post" action="new_message.php" style="display: inline">
                                    <input type="hidden" id="messageId" name="messageId" value="<?php echo $message[0]; ?>"/>
                                    <input type="submit" value="Answer" name="answer_button" class="btn btn-success"/>
                                </form>
                                <form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="display: inline">
                                    <input type="hidden" id="messageId" name="messageId" value="<?php echo $message[0]; ?>"/>
                                    <input type="submit" value="Delete" name="delete_button" class="btn btn-danger"/>
                                </form>
                            </div>
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
