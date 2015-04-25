<?php

use yii\db\Schema;
use yii\db\Migration;

class m150417_035517_add_user_read_table_to_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->userReadTable;
        $this->createTable($table, [
            'id'        =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id'   =>  'INT UNSIGNED NOT NULL',
            'post_id'   =>  'BIGINT UNSIGNED NOT NULL',
            'ip'        =>  'INT UNSIGNED NOT NULL',
            'created_at'=>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->addForeignKey('read_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
        
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->addForeignKey('read_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');        
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->userReadTable;
        $this->dropForeignKey('read_fk_post', $table);
        $this->dropForeignKey('read_fk_user', $table);
        $this->dropTable($table);
    }
}
