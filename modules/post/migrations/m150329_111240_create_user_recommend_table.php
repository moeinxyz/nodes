<?php

use yii\db\Schema;
use yii\db\Migration;

class m150329_111240_create_user_recommend_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->userRecommendTable;
        $this->createTable($table, [
            'user_id'           =>  'INT UNSIGNED NOT NULL',            
            'post_id'           =>  'BIGINT UNSIGNED NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`post_id`,`user_id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        
        $refTable   =   Yii::$app->getModule('user')->userTable;
        $this->addForeignKey('recommend_fk_user',$table, 'user_id', $refTable, 'id','CASCADE','CASCADE');
        $refTable   =   Yii::$app->getModule('post')->postTable;
        $this->addForeignKey('recommend_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');        
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->userRecommendTable;
        $this->dropForeignKey('recommend_fk_user', $table);
        $this->dropForeignKey('recommend_fk_post', $table);
        $this->dropTable($table);
    }
}
