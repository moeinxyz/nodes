<?php

use yii\db\Schema;
use yii\db\Migration;

class m150629_101927_add_last_post_suggestion_cron_to_user extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'last_post_suggestion', 'timestamp NOT NULL AFTER `notifications_count`');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'last_post_suggestion');
    }
    
}
