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
    <title>Messages</title>

    <!-- css (href ne fonctionne pas quand ej fais un dossier /css/fichiers.css)-->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php






?>


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
                     foreach($messages as $message):
                         echo $message; ?>
                        <tr>
                            <td><?= $message[5]; ?></td>
                            <td><?= $message[3]; ?></td>
                            <td><?= $message[1]; ?></td>
                            <td>
                                <button type="button" class="btn btn-primary"><i class="far fa-eye"></i></button>
                                <button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
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
