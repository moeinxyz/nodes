<?php

use yii\db\Schema;
use yii\db\Migration;

class m150328_230747_add_name_to_social extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->addColumn($table, 'name', 'VARCHAR(128) NOT NULL AFTER `media_id`');
        $this->addColumn($table, 'url', 'TEXT NOT NULL AFTER `name`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->dropColumn($table, 'url');
        $this->dropColumn($table, 'name');
    }
}
