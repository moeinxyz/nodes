Nodes
================================

Nodes is a mixture of blogging platform and social media which make reading and writing enjoyable.


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
composer global require "fxp/composer-asset-plugin:1.0.0"
composer install
~~~

### Configure Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```


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