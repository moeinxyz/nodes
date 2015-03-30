<?php

use yii\db\Schema;
use yii\db\Migration;

class m150325_210845_add_social_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->createTable($table, [
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'type'              =>  'ENUM("TWITTER","FACEBOOK","LINKEDIN") DEFAULT "FACEBOOK"',
            'media_id'          =>  'VARCHAR(64) NOT NULL',
            'token'             =>  'text NOT NULL',
            'status'            =>  'ENUM("ACTIVE","DEACTIVE") NOT NULL DEFAULT "ACTIVE"',
            'auth'              =>  'ENUM("AUTH", "DEAUTH","DELTED") NOT NULL DEFAULT "AUTH"',
            'share'             =>  'ENUM("POST","ALL") NOT NULL DEFAULT "POST"',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'user_id'           =>  'INT UNSIGNED NOT NULL',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $this->addForeignKey('social_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->dropForeignKey('social_fk_user', $table);
        $this->dropTable($table);
    }
}
