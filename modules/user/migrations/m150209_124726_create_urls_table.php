<?php

use yii\db\Schema;
use yii\db\Migration;

class m150209_124726_create_urls_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('user')->urlTable;
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->createTable($table, array(
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'user_id'           =>  'INT UNSIGNED NOT NULL',
            'url'               =>  'VARCHAR(512) NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'type'              =>  "ENUM('SITE','LINKEDIN','GITHUB','FACEBOOK','GOOGLE_PLUS','TWITTER','YOUTUBE','INSTAGRAM','BITBUCKET','TUMBLER','FLICKR','VK','ASKFM','PNTEREST') NOT NULL DEFAULT 'SITE'",
            'status'            =>  "ENUM('ACTIVE','DEACTIVE') NOT NULL DEFAULT 'ACTIVE'",
            'PRIMARY KEY(`id`)',
        ),'ENGINE=InnoDB  DEFAULT CHARSET=utf8');                
        
        $this->addForeignKey('url_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->urlTable;
        $this->dropForeignKey('url_fk_user', $table);
        $this->dropTable($table);
    }
}
