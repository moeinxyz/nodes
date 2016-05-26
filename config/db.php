<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => $_ENV['DB_DNS'],
    'emulatePrepare' => true,    
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',    
    'tablePrefix' => $_ENV['DB_PREFIX'],
    'enableSchemaCache' => true,    
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',    
];
