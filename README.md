Lucas Gianinetti && Nicolas Hungerbühler 
___
# STI -Projet 1


## Mise en place
______

### Lancement du serveur et installation de la base de données

Pour une distribution Linux :
```bash
git clone git@github.com:Grimlix/STI-Projet1.git
cd STI-Project1
sudo chmod +x setup.sh
sudo ./setup.sh
```

Pour Windows, il est nécessaire de modifier le fichier **setup_with_path.sh** et d'affecter à la variable path le path du dossier du projet
```bash
git clone git@github.com:Grimlix/STI-Projet1.git
cd STI-Project1
sudo chmod +x setup_with_path.sh
sudo ./setup_with_path.sh
```
### Arrêter le serveur 
docker stop sti_project

### Accès au site web

* Les différentes pages se trouvent à l'adresse htpp://localhost:8080/[nom_page]
* Un administrateur par défaut est inséré dans la base de données :
  * username: admin
  * password: admin
* Aucune page ne permet de supprimer un administrateur une fois celui-ci créé. Dans le cas ou il faudrait en supprimer un, il faut :
  * Accéder à la page d'administration de la base de donnée : http://localhost:8080/phpliteadmin.php
  * Accéder à la table users.
  * Supprimer l'entrée correspondant à l'administrateur non désiré.

## Description des différentes pages
___

### login.php

* Page sur laquelle l'utilisateur arrive lorsqu'il n'est pas connecté. Dans le cas ou il essaye d'accéder à une autre page, il est redirigé sur celle-ci.

* L'utilisateur peut se logger.

### sign_up.php

* L'utilisateur peut créer son compte.

### mailbox.php

* Page sur laquelle l'utilisateur est redirigé après son log in.
* Cette page affiche les informations des messages reçus par l'utilisateur (et lui permet de :
    * répondre à un message.
    * supprimer un message.
    * accéder au contenu d'un message. 
* Dans le cas ou cet utilisateur est un administrateur, il peut, en plus des fonctionalités disponibles à l'utilisateur lambda :
  * accéder à la page pour créer un utilisateur.
  * accéder à la page permettant d'administrer les utilisateurs.

### new_message.php

* Page depuis laquelle un utilisateur peut envoyer un message / répondre à un message.

### message_details.php

* Page sur laquelle le contenu d'un message est affiché.

### admin.php

* Page à laquelle seuls les administrateurs ont accès, une liste de tous les utilisateurs (non administrateur) est affichée.
* Les administrateurs peuvent :
  * Modifier les données d'un utilisateur :
    * mot de passe
    * rôle (Administrateur/Collaborateur)
    * validité (Enable / Disable)
  * Supprimer un utilisateur.

### add_user.php

* Page à laquelle seuls les administrateurs ont accès.
* Ils peuvent ajouter un utilisateur (username, password, rôle, validité).