<?php

use yii\db\Schema;
use yii\db\Migration;

class m150722_091740_add_priority_to_user_to_read extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->addColumn($table, 'priority', 'TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER `score`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->dropColumn($table, 'priority');
    }
}
