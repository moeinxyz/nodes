<?php

use yii\db\Schema;
use yii\db\Migration;

class m150317_193833_create_unwant_to_follow_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('user')->unwantToFollowTable;
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->createTable($table, [
            'user_id'               =>  'INT UNSIGNED NOT NULL',
            'unwanted_user_id'      =>  'INT UNSIGNED NOT NULL',
            'frequency'             =>  'INT UNSIGNED NOT NULL DEFAULT 1',
            'PRIMARY KEY(`user_id`,`unwanted_user_id`)',
        ],'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $this->addForeignKey('unwanted_fk1_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
        $this->addForeignKey('unwanted_fk2_user',$table, 'unwanted_user_id', $refTable, 'id','CASCADE','CASCADE');        
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->unwantToFollowTable;
        $this->dropForeignKey('unwanted_fk1_user', $table);
        $this->dropForeignKey('unwanted_fk2_user', $table);
        $this->dropTable($table);
    }
}
