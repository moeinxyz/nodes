<?php

use yii\db\Schema;
use yii\db\Migration;

class m150304_170734_add_autosave_field_to_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($table, 'autosave_content', 'LONGTEXT NOT NULL AFTER `content`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($table, 'autosave_content');
    }
}
