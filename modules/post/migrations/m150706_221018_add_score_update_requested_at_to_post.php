<?php

use yii\db\Schema;
use yii\db\Migration;

class m150706_221018_add_score_update_requested_at_to_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($table, 'score_update_requested_at', 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `score_updated_at`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($table, 'score_update_requested_at');
    }
}
