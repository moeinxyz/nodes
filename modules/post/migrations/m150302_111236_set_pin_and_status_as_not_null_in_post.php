<?php

use yii\db\Schema;
use yii\db\Migration;

class m150302_111236_set_pin_and_status_as_not_null_in_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'status', 'ENUM("DRAFT","PUBLISH","TRASH","DELETE","WRITTING") NOT NULL DEFAULT "WRITTING"');
        $this->alterColumn($table, 'pin', 'ENUM("ON","OFF") NOT NULL DEFAULT "OFF"');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'status', 'ENUM("DRAFT","PUBLISH","TRASH","DELETE","WRITTING") DEFAULT "WRITTING"');
        $this->alterColumn($table, 'pin', 'ENUM("ON","OFF")');
    }
}
