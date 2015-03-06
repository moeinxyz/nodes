<?php

use yii\db\Schema;
use yii\db\Migration;

class m150209_185335_create_security_token_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('user')->tokenTable;
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->createTable($table, array(
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id'           =>  'INT UNSIGNED NOT NULL',
            'token'             =>  'CHAR(32) NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'type'              =>  "ENUM('ACCOUNT_ACTIVATION','RESET_PASSWORD','CHANGE_EMAIL') NOT NULL DEFAULT 'ACCOUNT_ACTIVATION'",
            'status'            =>  "ENUM('USED','UNUSED') NOT NULL DEFAULT 'UNUSED'",
            'PRIMARY KEY(`id`)',
        ),'ENGINE=InnoDB  DEFAULT CHARSET=utf8');                
        
        $this->addForeignKey('token_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->tokenTable;
        $this->dropForeignKey('token_fk_user', $table);
        $this->dropTable($table);
    }
}
