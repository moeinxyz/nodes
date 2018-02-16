<?php

use yii\db\Schema;
use yii\db\Migration;

class m150808_061350_add_last_content_activity_digest extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'last_content_activity_mail', 'timestamp NOT NULL AFTER `last_digest_mail`');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'last_content_activity_mail');            
    }
}
