Si vous utilisez l'image Docker proposée pour le cours, vous pouvez copier directement le repertoire "site" et son contenu (explications dans la donnée du projet).

Le repertoire "site" contient deux repertoires :

    - databases
    - html

Le repertoire "databases" contient :

    - database.sqlite : un fichier de base de données SQLite

Le repertoire "html" contient :

    - exemple.php : un fichier php qui réalise des opérations basiques SQLite sur le fichier contenu dans le repertoire databases
    - helloworld.php : un simple fichier hello world pour vous assurer que votre container Docker fonctionne correctement
    - phpliteadmin.php : une interface d'administration pour la base de données SQLite qui se trouve dans le repertoire databases

Le mot de passe pour phpliteadmin est "admin".



Commande pour lancer le docker (localhost:8080/example.php) :


docker run -ti -v "C:\Users\nichu\Desktop\HEIG-VD\Annee3\STI\STI-Projet1\STI-Projet1\site":/usr/share/nginx/ -d -p 8080:80 --name
sti_project --hostname sti arubinst/sti:project2018


