<?php

use yii\db\Schema;
use yii\db\Migration;

class m150207_205408_add_auth_key extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'auth_key', 'VARCHAR(32) NOT NULL AFTER `email`');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'auth_key');
    }
}
