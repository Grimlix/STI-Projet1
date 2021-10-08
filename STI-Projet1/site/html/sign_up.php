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
     <title>PHP Test</title>

     <!-- css (href ne fonctionne pas quand ej fais un dossier /css/fichiers.css)-->
     <link rel="stylesheet" href="bootstrap.min.css">
     <link rel="stylesheet" href="style.css">

 </head>
 <body>

    <?php

        if (isset($_POST['submit']) && !empty($_POST['username'])
            && !empty($_POST['password'])){

            $username = $_POST['username'];
            $password = $_POST['password'];

            $create_user = "INSERT INTO users (username, password, role, validity)
                            VALUES ('{$username}', '{$password}', 0, 0)";

            $file_db->exec($create_user);
        }



    ?>

     <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
         <div class="LoginBox">
             <div class="form-group row">
                 <label for="inputUser" class="col-sm-2 col-form-label">Username</label>
                 <div class="col-sm-10">
                     <input type="text" class="form-control" name="username" placeholder="Username">
                 </div>
             </div>
             <div class="form-group row">
                 <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                 <div class="col-sm-10">
                     <input type="password" class="form-control" name="password" placeholder="Password">
                 </div>
             </div>
             <div class="form-group row">
                 <div class="offset-sm-2 col-sm-10">
                     <input type="submit" value="Create" name="submit" class="btn btn-primary"/>
                 </div>
             </div>
         </div>
     </form>


 </body>
</html>