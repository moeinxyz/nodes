<?php

use yii\db\Schema;
use yii\db\Migration;

class m150220_002507_add_setting_to_user extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'content_activity',    "ENUM('FULL','DIGEST','OFF') DEFAULT 'DIGEST'");
        $this->addColumn($table, 'publisher_activity',  "ENUM('FULL','DIGEST','OFF') DEFAULT 'DIGEST'");
        $this->addColumn($table, 'social_activity',     "ENUM('FULL','DIGEST','OFF') DEFAULT 'DIGEST'");
        $this->addColumn($table, 'reading_list',        "ENUM('DAILY','WEEKLY','OFF') DEFAULT 'DAILY'");
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'content_activity');
        $this->dropColumn($table, 'publisher_activity');
        $this->dropColumn($table, 'social_activity');
        $this->dropColumn($table, 'reading_list');
    }
}
