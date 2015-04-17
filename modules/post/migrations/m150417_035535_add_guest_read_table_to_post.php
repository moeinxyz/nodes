<?php

use yii\db\Schema;
use yii\db\Migration;

class m150417_035535_add_guest_read_table_to_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->guestReadTable;
        $this->createTable($table, [
            'id'        =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'post_id'   =>  'BIGINT UNSIGNED NOT NULL',
            'uuid'      =>  'CHAR(32) NOT NULL',
            'ip'        =>  'INT UNSIGNED NOT NULL',
            'useragent' =>  'CHAR(32) NOT NULL',//save md5 of useragent
            'created_at'=>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->addForeignKey('readguest_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');        
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->guestReadTable;
        $this->dropForeignKey('readguest_fk_post', $table);
        $this->dropTable($table);
    }
}
