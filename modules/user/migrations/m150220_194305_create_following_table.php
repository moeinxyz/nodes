<?php

use yii\db\Schema;
use yii\db\Migration;

class m150220_194305_create_following_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('user')->followingTable;
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->createTable($table, [
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id'           =>  'INT UNSIGNED NOT NULL',
            'followed_user_id'  =>  'INT UNSIGNED NOT NULL',
            'status'            =>  'ENUM("ACTIVE","DEACTIVE") NOT NULL DEFAULT "ACTIVE"',
            'PRIMARY KEY(`id`)',
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');

        $this->addForeignKey('following_fk1_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
        $this->addForeignKey('following_fk2_user',$table, 'followed_user_id', $refTable, 'id','CASCADE','CASCADE');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->followingTable;
        $this->dropForeignKey('following_fk2_user', $table);
        $this->dropForeignKey('following_fk1_user', $table);
        $this->dropTable($table);
    }
}
