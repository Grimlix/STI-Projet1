#!/bin/bash

sti_project_docker=$(docker ps -a | grep 'sti_project')
path="C:\Users\nichu\Desktop\HEIG-VD\Annee3\STI\STI-Projet1\STI-Projet1\site"

if [ -z "$sti_project_docker" ]
then
	echo 'Image is not built, we are building it.'
	docker run -ti -v $path://usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018
else
	echo 'Image already built, we are running it'
	docker_started=$(docker ps | grep 'sti_project')
	if [ ! -z "$docker_started" ]
	then
		echo 'Image is already started'
	else
		echo 'Starting the image'
		docker start sti_project
		echo 'Starting the servers now'
	fi

fi

echo 'Starting the servers now'
docker exec -u root sti_project service nginx start
echo 'nginx started'
docker exec -u root sti_project service php5-fpm start
echo 'php5-fpm started'

echo 'Database setup'
curl http://localhost:8080/DB_init.php
curl http://localhost:8080/DB_init.php
