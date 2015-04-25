<?php

use yii\db\Schema;
use yii\db\Migration;

class m150405_154533_add_social_content_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('social')->socialContentTable;

        $this->createTable($table, [
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'post_id'           =>  'BIGINT UNSIGNED NOT NULL',
            'social_id'         =>  'BIGINT UNSIGNED NULL',
            'verbose'           =>  'text NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $refTable   =   Yii::$app->getModule('social')->socialTable;        
        $this->addForeignKey('socialcontent_fk_social',$table, 'social_id', $refTable, 'id','SET NULL','CASCADE');        
        
        $refTable   =   Yii::$app->getModule('post')->postTable;        
        $this->addForeignKey('socialcontent_fk_post',$table, 'post_id', $refTable, 'id','CASCADE','CASCADE');                
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('social')->socialContentTable;
        $this->dropForeignKey('socialcontent_fk_post', $table);
        $this->dropForeignKey('socialcontent_fk_social', $table);
        $this->dropTable($table);
    }
}
