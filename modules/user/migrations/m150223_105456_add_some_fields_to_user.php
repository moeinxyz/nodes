<?php

use yii\db\Schema;
use yii\db\Migration;

class m150223_105456_add_some_fields_to_user extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'followers_count', 'INT UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn($table, 'following_count', 'INT UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn($table, 'posts_count', 'INT UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn($table, 'recommended_count', 'INT UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn($table, 'notifications_count', 'INT UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'followers_count');
        $this->dropColumn($table, 'following_count');
        $this->dropColumn($table, 'posts_count');
        $this->dropColumn($table, 'recommended_count');
        $this->dropColumn($table, 'notifications_count');
    }
}
