# SnowPassion
<img src="http://snowpassion.gdpweb.fr/assets/img/logo-small.png"  width="100" />

This project has been realized as part of project 6 **Openclassroom** training.

SnowPassion is a collaborative website to introduce snowboarding to the general public and help them learn tricks.
- Compatible with all major browsers
- **Example online** [SnowPassion](http://snowpassion.gdpweb.fr/ "Heading link")


## Server Requirements of the Web App
- Application Server PHP 7.1 or higher
- Database MySQL >= 5.7.11

## Installation
- Clone the master branch
- Install dependencies with: composer install
- Create database: bin/console doctrine:database:create
- Update database: bin/console doctrine:schema:update --force
- Load data fixtures: bin/console doctrine:fixtures:load
- Run PHP's built-in Web Server: bin/console server:run
- Navigate to: localhost:8000

## Tests
- Create tests database: bin/console doctrine:schema:create --env=test
- Update database: bin/console doctrine:schema:update --env=test
- Load tests data fixtures: bin/console doctrine:fixtures:load --env=test
- Run units and functionals tests: vendor/bin/simple-phpunit

## Theme
This project use Start Bootstrap theme: https://startbootstrap.com/template-overviews/clean-blog/
## Licence

[![Open Source Love](https://badges.frapsoft.com/os/v2/open-source.png?v=103)](https://github.com/ellerbrock/open-source-badges/)

## Codacy
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/cf093838a8494b799381b72c99c191f4)](https://www.codacy.com/app/gdpweb_3/SnowPassion?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=gdpweb/SnowPassion&amp;utm_campaign=Badge_Grade)
