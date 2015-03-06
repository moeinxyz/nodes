<?php

use yii\db\Schema;
use yii\db\Migration;

class m150213_154935_set_password_field_len_60 extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->alterColumn($table, 'password', 'CHAR(60) NULL');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->alterColumn($table, 'password', 'VARCHAR(32) NULL');
    }
}
