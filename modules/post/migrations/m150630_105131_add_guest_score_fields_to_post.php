<?php

use yii\db\Schema;
use yii\db\Migration;

class m150630_105131_add_guest_score_fields_to_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($table, 'score', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER `user_id`');
        $this->addColumn($table, 'score_updated_at', 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `score`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($table, 'score_updated_at');
        $this->dropColumn($table, 'score');
    }
}
