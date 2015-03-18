<?php

use yii\db\Schema;
use yii\db\Migration;

class m150318_124848_create_uploaded_image extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->imageTable;
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->createTable($table, [
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'url'               =>  'VARCHAR(255) NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'post_id'           =>  'BIGINT UNSIGNED NOT NULL',          
            'PRIMARY KEY(`id`)',            
            'UNIQUE KEY `url_UNIQUE` (`url`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $this->addForeignKey('image_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');  
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->imageTable;
        $this->dropForeignKey('image_fk_post', $table);
        $this->dropTable($table);
    }
}
