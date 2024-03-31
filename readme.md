# Guild Wars 2 Raid Planner

## Author
Orden14

## Project startup
This is a Symfony project running on Symfony 7. In order to run the project easily, you need the following tools :
- Php 8.3
- Symfony CLI
- Composer
- Yarn
- Docker

These are custom commands to help you run the project :
- docker-compose up (this docker-compose contains a MariaDB database, Phpmyadmin, and MailDev)
- yarn run dependencies (executes composer install, yarn install, and yarn build)
- yarn run truncate-database (clear database and loads data fixtures)
- yarn run server-start (starts a local dev server on port 8001)

Project will be accessible on http://localhost:8001  
MailDev will be accessible on http://localhost:1080/

## Test users
Among all the generated users, there are default users available for easier testing :
- Admin : (username : admin / password : admin)
- Member : (username : member / password : member)
- Guest : (username : guest / password : guest)

# Contact
* Discord : orden14
* In game : Jiho.1035
