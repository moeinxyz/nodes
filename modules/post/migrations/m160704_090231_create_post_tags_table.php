<?php

use yii\db\Migration;

/**
 * Handles the creation for table `post_tags_table`.
 */
class m160704_090231_create_post_tags_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTagsTable;
        $this->createTable($table, [
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'post_id'           =>  'BIGINT UNSIGNED NOT NULL',
            'tag_id'            =>  'BIGINT UNSIGNED NOT NULL',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'        =>  'timestamp NOT NULL',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');

        $postTable      =   Yii::$app->getModule('post')->postTable;
        $tagTable       =   Yii::$app->getModule('post')->tagTable;
        $this->addForeignKey('posttags_fk_post',$table, 'post_id', $postTable, 'id','CASCADE','CASCADE');
        $this->addForeignKey('posttags_fk_tag',$table, 'post_id', $tagTable, 'id','CASCADE','CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTagsTable;
        $this->dropForeignKey('posttags_fk_tag', $table);
        $this->dropForeignKey('posttags_fk_post', $table);
        $this->dropTable($table);
    }
}
