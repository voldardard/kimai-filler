


# Quick start

## Requirements
* Mysql 5.7 or higher / Mariadb 10 or higher / Postgres 9 or higher
* apache2
* Composer
* PHP >= 7.2.5
* BCMath PHP Extension
* Ctype PHP Extension
* Fileinfo PHP extension
* JSON PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

## Installation
1) Put the docroot in the public/ folder at the root of the installation
1) $ git clone https://github.com/voldardard/kimai-filler.git
1) $ composer install
1) $ composer update
1) $ chmod 775 ./storage/
1) $ chmod 775 ./bootstrap/cache
1) $ cp .env.example .env
1) $ chmod 770 .env
1) $ php artisan key:generate
1) Add missing information in .env file
