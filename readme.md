# Guild Wars 2 Raid Planner

## Author

Orden14  
https://github.com/Orden14/RaidPlanner

## About

RaidPlanner is a tool for planning Guild Wars 2 instanced content.  
Current version is made in French for the guild Virtual Squirrels.  
Virtual Squirrels official website: https://virtualsquirrels.fr/

## Project requirements

- Php 8.3 or above [\<link\>](https://www.php.net/downloads)
- Symfony CLI [\<link\>](https://symfony.com/download#step-1-install-symfony-cli)
- Composer [\<link\>](https://getcomposer.org/doc/00-intro.md)
- Node 20 or above [\<link\>](https://nodejs.org/en/download/)
- Yarn [\<link\>](https://yarnpkg.com/getting-started/install)

###### These are custom commands to help you run the project for the first time in a development environment:

1. Docker-compose with the database and phpmyadmin only (for a full setup, check `docker-compose.yaml`) :
```bash
docker-compose -f docker-compose.light.yaml up -d
```

2. Download all dependencies and build assets :
```bash
yarn dependencies
```

3. Clear database and loads data fixtures :
```bash
yarn truncate-database
```

4. Start the server :
```bash
symfony serve
```

Don't forget to set up the .env and .env.test files with your own database credentials if you don't use one of the provided docker-compose file.

Project will be accessible on http://localhost:8000  (or another port if 8000 is already in use)

###### These are custom commands to help you during development:

1. builds CSS and JS with Webpack
```bash
yarn build
```

2. constantly watches for changes in CSS and JS files and compile them on the go
```bash
yarn watch
```

## Test users
Among all the generated users, there are default users available for easier testing (passwords are the same as usernames):
- admin
- member
- trial
- old_member
- guest

#### Commands related to tests:

1. Build codeception classes
```bash
vendor/bin/codecept build
```

2. Run all tests
```bash
yarn test
```

#### Gitlab Action
The project is set up with a Gitlab Action that runs the tests on every merge requests to the main branch.  
Action config file : `/.github/workflows/symfony.yml`

# Contact
* Discord: `orden14`
