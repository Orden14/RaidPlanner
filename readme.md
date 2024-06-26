# Guild Wars 2 Raid Planner

## Author
Orden14  
https://github.com/Orden14/RaidPlanner

## About
RaidPlanner is a tool for planning Guild Wars 2 instanced content.  
Current version is made in French for the guild Virtual Squirrels.  
Virtual Squirrels official website: https://virtualsquirrels.fr/

## Project startup
This is a Symfony project running on Symfony 6.4. To run the project easily, you need the following tools:
- Php 8.3
- Symfony CLI
- Composer
- Yarn (with node >21)

###### These are custom commands to help you run the project for the first time in a development environment:
- docker-compose -f docker-compose.light.yaml up -d (docker-compose with the database and phpmyadmin only, for a full setup, check docker-compose.yaml)
- yarn dependencies (executes composer install, yarn install, and yarn build)
- yarn truncate-database (clear database and loads data fixtures)
- symfony serve (starts the local development server)

Don't forget to set up the .env and .env.test files with your own database credentials.

Project will be accessible on http://localhost:8000  (or another port if 8000 is already in use)

###### These are custom commands to help you during development:
- yarn build (builds CSS and JS with Webpack)
- yarn watch (constantly watches for changes in CSS and JS files and compile them on the go)

## Test users
Among all the generated users, there are default users available for easier testing (passwords are the same as usernames):
- admin
- member
- trial
- old_member
- guest

#### Commands related to tests:
- vendor/bin/codecept build (runs the build command for Codeception)
- yarn test

#### Gitlab Action
The project is set up with a Gitlab Action that runs the tests on every merge requests to the main branch.  
Action config file: .github/workflows/symfony.yml

# Contact
* Discord: orden14
