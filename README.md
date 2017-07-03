# basic-crud-with-symfony
Basic crud operations with Symfony 3. It is just for basic training.

## Setup
    git clone https://github.com/abpolat/basic-crud-with-symfony.git
    cd basic-crud-with-symfony

    After configure your database conf. please use the following commands to create database

    php bin/console doctrine:schema:create

    Also you need to create one account to login the system. For that please edit following file and afterwards run below command.

    \src\AppBundle\DataFixtures\ORM\LoadUserData.php
    php bin/console doctrine:fixtures:load --append

## PS
  I've tried to make basic crud app with Symfony 3. If you have any question, please don't hesitate and contact with me.


## Author

Burak Polat

[bytok.net](http://bytok.net)