<?php

use yii\db\Schema;
use yii\db\Migration;

class m150805_050158_add_pure_text_to_comments extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->addColumn($table, 'pure_text', 'TEXT NOT NULL AFTER `text`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->dropColumn($table, 'pure_text');
    }
}
