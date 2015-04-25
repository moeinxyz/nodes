<?php

use yii\db\Schema;
use yii\db\Migration;

class m150318_173151_add_cover_and_text_to_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($table, 'cover', 'ENUM("NOCOVER","BYCOVER") DEFAULT "NOCOVER" AFTER `autosave_content`');
        $this->addColumn($table, 'pure_text', 'TEXT DEFAULT NULL AFTER `autosave_content`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($table, 'cover');
        $this->dropColumn($table, 'pure_text');
    }
    
}
