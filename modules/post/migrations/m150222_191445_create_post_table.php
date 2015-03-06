<?php

use yii\db\Schema;
use yii\db\Migration;

class m150222_191445_create_post_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $refTable   =   Yii::$app->getModule('user')->userTable;
        
        $this->createTable($table, [
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'url'               =>  'VARCHAR(256) NOT NULL',
            'title'             =>  'VARCHAR(256) NOT NULL',
            'content'           =>  'LONGTEXT NOT NULL',
            'pin'               =>  'ENUM("ON","OFF")',
            'comments_count'    =>  'BIGINT UNSIGNED NOT NULL DEFAULT 0',
            'status'            =>  'ENUM("DRAFT","PUBLISH","TRASH","DELETE")',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'        =>  'timestamp NOT NULL',
            'user_id'           =>  'INT UNSIGNED NOT NULL',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $this->addForeignKey('post_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropForeignKey($table, 'post_fk_user');
        $this->dropTable($table);
    }
}
