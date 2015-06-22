<?php

use yii\db\Schema;
use yii\db\Migration;

class m150620_085956_add_recommended_post_for_user_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->createTable($table, [
            'id'        =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id'   =>  'INT UNSIGNED NOT NULL',
            'post_id'   =>  'BIGINT UNSIGNED NOT NULL',
            'score'     =>  'TINYINT NOT NULL',
            'created_at'=>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->addForeignKey('user_toread_fk_post',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
        
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->addForeignKey('user_toread_fk_user',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');        
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->dropForeignKey('user_toread_fk_post', $table);
        $this->dropForeignKey('user_toread_fk_user', $table);
        $this->dropTable($table);        
    }
}
