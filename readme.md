# Guild Wars 2 Raid Planner

## Author
Orden14  
https://github.com/Orden14/RaidPlanner

## About
RaidPlanner is a tool for planning Guild Wars 2 instanced content.  
I have no plan to make an english translation of the app for now.  
/!\ Application is still in early-stage development /!\

## Project startup
This is a Symfony project running on Symfony 7. To run the project easily, you need the following tools :
- Php 8.3
- Symfony CLI
- Composer
- Yarn
- Docker

###### These are custom commands to help you run the project for the first time :
- docker-compose up (this docker-compose contains a MariaDB database, Phpmyadmin, and MailDev)
- yarn dependencies (executes composer install, yarn install, and yarn build)
- yarn truncate-database (clear database and loads data fixtures)
- yarn server-start (starts a local dev server on port 8001)

Project will be accessible on http://localhost:8001  
MailDev will be accessible on http://localhost:1080/

###### These are custom commands to help you during development :
- yarn build (builds CSS and JS with Webpack Encore)
- yarn watch (constantly watches for changes in CSS and JS files and compile them on the go)

## Test users
Among all the generated users, there are default users available for easier testing (passwords are the same as usernames) :
- Admin
- Member
- Trial
- Old Member
- Guest

## Test using Codeception
This app uses Codeception for acceptance, functional and unit tests.
The Webdriver currently set up in the project is for windows x64. If you are using another OS, remove geckodriver.exe in the root directory of the project and use the corresponding geckodriver from https://github.com/mozilla/geckodriver/releases.

###### Custom commands related to tests :
- vendor/bin/codecept build (runs the build command for Codeception)
- yarn test (executes all tests)
- yarn test Acceptance/Functional/Unit (executes a specific test suite)

# Contact
* Discord : orden14
* In game : Jiho.1035
