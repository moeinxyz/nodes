Nodes
================================

Nodes is a mixture of blogging platform and social media which makes reading and writing enjoyable.


REQUIREMENTS
------------
* Web Server
* At Least PHP v5.4.0
* Composer
* Relational Database - e.g: MySQL
* RabbitMQ
* Suppervisor/PM2 for daemon
* FTP Server for CDN

INSTALLATION
------------

### Install Dependencies

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You need following commands to install project dependencies.
~~~
composer global require "fxp/composer-asset-plugin:1.0.4"
composer install
~~~

### Configuration

Copy `config/.env.sample` to `config/.env` and fill it with real data. For instance:

~~~
# Database Configuration
DB_DNS="mysql:host=localhost;dbname=nodes"
DB_USERNAME="root"
DB_PASSWORD="123456"
DB_PREFIX="tbl_"
...
~~~

### Run Migrations

cd to project root and follow below commands to run migrations

~~~
./yii migrate --migrationPath=@app/modules/user/migrations
./yii migrate --migrationPath=@app/modules/post/migrations
./yii migrate --migrationPath=@app/modules/embed/migrations
./yii migrate --migrationPath=@app/modules/social/migrations
~~~

### Minify Assets

In production you need to minify assets, So run following command.

~~~
./yii asset assets.php config/assets-prod.php
~~~

### Other Configuration

There are many options to change, e.g: ftp configuration, urls, queues and ...

All of them can be found in `config` directory