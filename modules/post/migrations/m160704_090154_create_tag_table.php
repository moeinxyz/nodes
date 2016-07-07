<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tag_table`.
 */
class m160704_090154_create_tag_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->tagTable;
        $this->createTable($table, [
            'id'                =>  'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT',
            'tag'               =>  'VARCHAR(255) NOT NULL',
            'frequency'         =>  'BIGINT UNSIGNED NOT NULL DEFAULT 0',
            'created_at'        =>  'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'        =>  'timestamp NOT NULL',
            'PRIMARY KEY(`id`)'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $table      =   Yii::$app->getModule('post')->tagTable;
        $this->dropTable($table);
    }
}
