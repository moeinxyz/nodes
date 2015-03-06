<?php

use yii\db\Schema;
use yii\db\Migration;

class m150205_181738_create_user_table extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->createTable($table, array(
            'id'                =>  'INT UNSIGNED NOT NULL AUTO_INCREMENT',
            'username'          =>  'VARCHAR(64) NOT NULL',
            'email'             =>  'VARCHAR(128) NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'type'              =>  "ENUM('ADMIN','USER') NOT NULL DEFAULT 'USER'",
            'status'            =>  "ENUM('ACTIVE','DEACTIVE','BLOCK') NOT NULL DEFAULT 'DEACTIVE'",
            // profile part
            'name'              =>  'VARCHAR(128) NULL',
            'tagline'           =>  'VARCHAR(256) NULL',
            'PRIMARY KEY(`id`)',
            'UNIQUE KEY `email_UNIQUE` (`email`)',
            'UNIQUE KEY `username_UNIQUE` (`username`)'
        ),'ENGINE=InnoDB  DEFAULT CHARSET=utf8');        
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropTable($table);
    }
}
