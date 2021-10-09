<?php

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
                        <th scope="col">Receiver</th>
                        <th scope="col">Sender</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Bootstrap 4 CDN and Starter Template</td>
                        <td>Cristina</td>
                        <td>2.846</td>
                        <td>
                            <button type="button" class="btn btn-primary"><i class="far fa-eye"></i></button>
                            <button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Bootstrap Grid 4 Tutorial and Examples</td>
                        <td>Cristina</td>
                        <td>3.417</td>
                        <td>
                            <button type="button" class="btn btn-primary"><i class="far fa-eye"></i></button>
                            <button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Bootstrap Flexbox Tutorial and Examples</td>
                        <td>Cristina</td>
                        <td>1.234</td>
                        <td>
                            <button type="button" class="btn btn-primary"><i class="far fa-eye"></i></button>
                            <button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
