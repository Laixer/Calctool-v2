# Install instructions

## Requirements

  - Git
  - Curl
  - Apache2
  - PostreSQL
  - PHP5
    - PHP-mcrypt
    - PHP-pgsql
    - PHP5-curl
  - Redis

## Setup
This setup is specific for __Debian 8__ (Jessie). This readme assumes a fully installed and updated Debian server is present.

#### Install composer
```sh
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/bin/composer
```

#### Install wkhtmltopdf (check for latest version)
```sh
$ wget http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-<version>_linux-jessie-amd64.deb
$ dpkg -i wkhtmltox-<version>_linux-jessie-amd64.deb
$ apt-get install -f
```

#### Fetch Calctool
```sh
$ cd /var/www/
$ git clone <repo>
$ cd calctool-v2
$ cp example.env.php .env.<env>.php
# Set config in .env.<env>.php according to environment
# Add hostname to bootstrap/start.php (only dev and local)
$ composer update
```

#### Configure apache
```sh
$ chown -R www-data: app/ public/ bootstrap/
$ a2enmod rewrite
# Set config in /etc/apache2/sites-enabled/
$ touch /var/www/calctool-v2/.revision
```

#### Load database
```sh
$ su postgres
$ psql
>\password
# Create database and role
$ /var/www/calctool-v2/artisan migrate --seed
```

### Todos

 - Install for Centos
 - Install with Nginx
 - Configuration daemons

