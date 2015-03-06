<?php

use yii\db\Schema;
use yii\db\Migration;

class m150306_121020_create_embed_table extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('embed')->embedTable;
        $this->createTable($table, [
            'id'        =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'hash'      =>  'CHAR(32) NOT NULL',
            'url'       =>  'TEXT NOT NULL',
            'type'      =>  'ENUM("OEMBED","EXTRACT") NOT NULL DEFAULT "OEMBED"',
            'frequnecy' =>  'BIGINT UNSIGNED NOT NULL DEFAULT 1',
            'response'  =>  'TEXT NOT NULL',
            'created_at'=>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'=>  'timestamp NOT NULL',
            'PRIMARY KEY(`id`)',
            'UNIQUE KEY `hash_UNIQUE` (`hash`)',
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('embed')->embedTable;
        $this->dropTable($table);
    }
}
