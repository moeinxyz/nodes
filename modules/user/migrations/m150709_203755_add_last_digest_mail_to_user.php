<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_203755_add_last_digest_mail_to_user extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'last_digest_mail', 'timestamp NOT NULL AFTER `last_post_suggestion`');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'last_digest_mail');            
    }
}
