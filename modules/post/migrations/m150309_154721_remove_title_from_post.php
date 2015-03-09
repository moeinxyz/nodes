<?php

use yii\db\Schema;
use yii\db\Migration;

class m150309_154721_remove_title_from_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($table, 'title');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($table, 'title', 'VARCHAR(256) NOT NULL AFTER `url`');
    }

}
