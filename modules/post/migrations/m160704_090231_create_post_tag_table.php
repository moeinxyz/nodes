<?php

use yii\db\Migration;

/**
 * Handles the creation for table `post_tag_table`.
 */
class m160704_090231_create_post_tag_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTagTable;
        $this->createTable($table, [
            'post_id'           =>  'BIGINT UNSIGNED NOT NULL',
            'tag_id'            =>  'BIGINT UNSIGNED NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'        =>  'timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP',
            'PRIMARY KEY(`post_id`,`tag_id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');

        $postTable      =   Yii::$app->getModule('post')->postTable;
        $tagTable       =   Yii::$app->getModule('post')->tagTable;
        $this->addForeignKey('post_tag_fk_post',$table, 'post_id', $postTable, 'id','CASCADE','CASCADE');
        $this->addForeignKey('post_tag_fk_tag',$table, 'tag_id', $tagTable, 'id','CASCADE','CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTagTable;
        $this->dropForeignKey('post_tag_fk_tag', $table);
        $this->dropForeignKey('post_tag_fk_post', $table);
        $this->dropTable($table);
    }
}
