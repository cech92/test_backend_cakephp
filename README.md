# CakePHP Application Skeleton

[![Build Status](https://app.travis-ci.com/cech92/test_backend_cakephp.svg?token=UGciGa13fe1TiqM9scHJ&branch=master)](https://app.travis-ci.com/cech92/test_backend_cakephp)

## Description
This project contains two simple REST APIs that use a `.csv` file as a datastore.
It was therefore not necessary to install and use any database.
In this project was used `Docker Compose`, `Travis` for the continuous integration
and `Swagger` for the automatic definition of the OpenAPI 3.0 specifications.

The following APIs have been implemented:
1. `/flyers.json`: Return a list of flyers, the function is able to handle the
following GET parameters:
   1. `page`: Number of the page used by pagination (default=1);
   2. `limit`: Number of items used by pagination (default=100);
   3. `fields`: A comma-separated list of fields to return;
   4. `filters[key]=value`: A key-value list used to filter the data.
2. `/flyers/{id}.json`: Return the flyer with the `id` provided, the function is able to handle the
   following GET parameters:
   1. `fields`: A comma-separated list of fields to return.

The OpenAPI 3.0 documentation can be found at the following path: `/docs`.

## Installation
### Using Ubuntu/Debian environment
1. Run update and install `php` and `libapache2-mod-php`:
```bash
sudo apt update
sudo apt install php libapache2-mod-php
```
2. Install the following extensions (`php-mbstring`, `php-intl`, `php-xml`):
```bash
sudo apt-get install php-mbstring
sudo apt-get install php-intl
sudo apt-get install php-xml
```
3. Install composer:
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
4. Run the project with `bin/cake server -p 8000`

### Using Docker
1. Install docker and docker-compose
2. Run with `docker-compose up`

## Testing
If you are using Docker, run: `docker-compose run cakephp vendor/bin/phpunit`.\
If you are in a local environment, run: `vendor/bin/phpunit`.

