ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

Installation

	clone or downbload the project at the following url :

    git clone https://github.com/cephalopodius/P8

    Change the .env file and put your database information
	
    Install dependencies with composer install command
 
    Create the database with the following command :

    php bin/console d:d:c

    setup the database with the migration and the fixtures with the following commands :

    php bin/console doctrine:migrations:migrate

    php app/console doctrine:fixtures:load 

    Congratulation, the project is ready !

More information :
	Blakfire's test result can be find in the Blackfire Evolution folder
	Uml diagram are in the Diagram folder
	Phpunit coverage test result is in the var/data folder