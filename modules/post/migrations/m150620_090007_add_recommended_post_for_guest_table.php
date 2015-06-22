<?php

use yii\db\Schema;
use yii\db\Migration;

class m150620_090007_add_recommended_post_for_guest_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->guestToReadTable;
        $this->createTable($table, [
            'id'        =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'post_id'   =>  'BIGINT UNSIGNED NOT NULL',
            'score'     =>  'TINYINT NOT NULL',
            'created_at'=>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->addForeignKey('guest_toread_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');        
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->guestToReadTable;
        $this->dropForeignKey('guest_toread_fk_post', $table);
        $this->dropTable($table);        
    }
}
