<?php

use yii\db\Schema;
use yii\db\Migration;

class m150209_055829_add_password_to_user extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'password', 'VARCHAR(32) NULL AFTER `email`');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'password');
    }
}
