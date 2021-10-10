<?php
session_start();
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

    /*
    ->query() : requête qui ne modifie pas la base de données
    ->fetch() : récupérer la donnée de la requête (toujours dans un array)
    ->fetchAll() : récupère tous array[0][]
    ->exec() : requête qui modifie la base de données

    */

    // delete button
    if (isset($_POST['delete_button'])){
        $id = $_POST['messageId'];
        $delete_message = "DELETE FROM messages WHERE id ='{$id}'";
        $file_db->exec($delete_message);
    }else if(isset($_POST['answer_button'])){
        header("Location:message.php");
        $_SESSION['messageId'] = $_POST['messageId'];
        exit();
    }else if(isset($_POST['details_button'])){
        header("Location:message_details.php");
        $_SESSION['messageId'] = $_POST['messageId'];
        exit();
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
    <form action="message.php" method="post">
        <input type="submit" name="button_new_message" value="New message">
    </form>
    <form action="admin.php" method="post">
        <input type="submit" name="button_admin" value="Admin">
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
                            <form role="form" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="display: inline">
                                <input type="hidden" id="messageId" name="messageId" value="<?php echo $message[0]; ?>"/>
                                <input type="submit" value="Details" name="details_button" class="btn btn-primary"/>
                                <input type="submit" value="Answer" name="answer_button" class="btn btn-success"/>
                                <input type="submit" value="Delete" name="delete_button" class="btn btn-danger"/>
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
