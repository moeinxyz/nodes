<?php

use yii\db\Schema;
use yii\db\Migration;

class m150324_210828_add_abuse_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->abuseTable;
        $this->createTable($table, [
            'id'        =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id'   =>  'INT UNSIGNED NOT NULL',
            'post_id'   =>  'BIGINT UNSIGNED NULL',
            'comment_id'=>  'BIGINT UNSIGNED NULL',
            'created_at'=>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`id`)'            
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->addForeignKey('abuse_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->addForeignKey('abuse_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');        
        $refTable   =   Yii::$app->getModule('post')->commentTable;
        $this->addForeignKey('abuse_fk_comment',$table, 'comment_id', $refTable, 'id','CASCADE','CASCADE');                
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->abuseTable;
        $this->dropForeignKey('abuse_fk_comment', $table);
        $this->dropForeignKey('abuse_fk_post', $table);
        $this->dropForeignKey('abuse_fk_user', $table);
        $this->dropTable($table);
    }
}
