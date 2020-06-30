## Backend

The REST API is made in PHP so a server with PHP 7.1 or higher is required.

```bash
$ git clone https://github.com/itsalb3rt/sheiley-shop-api.git
```

```bash
$ composer require itsalb3rt/sheiley-shop-api
```

### Database

Inside the *root* directory of the REST API enter `etc/sheiley_shop.sql` this file contains all the script from the database.

After executing the script in the Mysql database, it remains to enter the directory `config/config.php.ini` and set user database, username and password.

```ini
<?php return; ?>
; Database config
host=localhost
user=root
pass=toor
dbname=sheiley_shop
driver=mysql
charset=utf8
collation=utf8mb4_unicode_ci
prefix=""
port=""
```

### Backend production

In the `system/webroot/` directory you will find a file named `FrontController.php` inside this you must modify the constant `ENVIROMENT` and put the value `pro`.

## Docker

The `API` is ready for using with docker, copy the `.env.bak` file and rename it for `.env` and set your configuration.

```bash
$ docker-compose up -d
```

:warning: Is important if you use docker, go to `root/system/config/config.php.ini` and change the `host` for `sheiley-shop-db`.

:warning: If you do not use `Traefik`, remove the `labels` and the `networks` section of the `docker-compose.yml`

[Go back to main documentation](https://github.com/itsalb3rt/sheiley_shop_app)
