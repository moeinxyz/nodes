<?php

use yii\db\Schema;
use yii\db\Migration;

class m150808_120655_add_notification_mail_to_userToRead extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->addColumn($table,'notification_mail_status' , 'ENUM("SENT","NOT_SEND") DEFAULT "NOT_SEND" AFTER `priority`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->dropColumn($table, 'notification_mail_status');
    }
}
