<?php

use yii\db\Schema;
use yii\db\Migration;

class m150318_203918_create_comment_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->createTable($table, [
            'id'        =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id'   =>  'INT UNSIGNED NOT NULL',
            'post_id'   =>  'BIGINT UNSIGNED NOT NULL',
            'text'      =>  'TEXT NOT NULL',
            'status'    =>  'ENUM("PUBLISH","TRASH","ABUSE") DEFAULT "PUBLISH"',
            'created_at'=>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->addForeignKey('comment_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->addForeignKey('comment_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->dropForeignKey('comment_fk_post', $table);
        $this->dropForeignKey('comment_fk_user', $table);
        $this->dropTable($table);
    }
}
