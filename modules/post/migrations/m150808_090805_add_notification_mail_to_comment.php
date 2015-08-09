<?php

use yii\db\Schema;
use yii\db\Migration;

class m150808_090805_add_notification_mail_to_comment extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->addColumn($table,'notification_mail_status' , 'ENUM("SENT","NOT_SEND") DEFAULT "NOT_SEND" AFTER `post_author_seen`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->dropColumn($table, 'notification_mail_status');
    }
}
