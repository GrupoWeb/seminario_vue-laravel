# Proyecto de Seminario 

Proyecto de Seminario de Análisis, Diseño y Desarrollo : [Juan José Jolón Granados.](https://github.com/GrupoWeb)

## Datos Generales

* Carnet: 3190-16-16201
* Ingenieria en Sistemas


## Installation

``` bash
# clone the repo
$ git clone https://github.com/GrupoWeb/seminario_vue-laravel my-project

# go into app's directory
$ cd my-project/laravel

# install app's dependencies
$ composer install

# install app's dependencies
$ npm install
```

```
Copy file ".env.example", and change its name to ".env".
Then in file ".env" replace this database configuration:
* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=laravel
* DB_USERNAME=root
* DB_PASSWORD=

```

### If you choose MySQL

1. Install MySQL
2. Create database (this way or another)
``` bash
$ mysql -uroot -p
mysql> create database laravel;
```
Create a user with privileges to the laravel database (root user may not work while it requires a sudo)

3. Update .env file
Copy file ".env.example", and change its name to ".env".
Then in file ".env" complete database configuration:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Next step

``` bash
# in your app directory
# generate laravel APP_KEY
$ php artisan key:generate

# generate jwt secret
$ php artisan jwt:secret

# run database migration and seed
$ php artisan migrate:refresh --seed

```

```bash
# go to coreui directory
$ cd coreui

# install app's dependencies
$ npm install

```

## Usage

### Test
``` bash
# test
$ php vendor/bin/phpunit
```

### If you need separate backend and frontend

``` bash
# back to laravel directory
$ cd laravel

# start local server
$ php artisan serve

$ cd coreui

$ npm run serve


#
```
Open your browser with address: [localhost:8000](localhost:8000) 

### When you have project open in browser

Click "Login" on sidebar menu and log in with credentials:

* E-mail: _admin@admin.com_
* Password: _password_

This user has roles: _user_ and _admin_

--- 
