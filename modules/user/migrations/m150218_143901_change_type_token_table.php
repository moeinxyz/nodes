<?php

use yii\db\Schema;
use yii\db\Migration;

class m150218_143901_change_type_token_table extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('user')->tokenTable;
        $this->alterColumn($table, 'type', "ENUM('ACCOUNT_ACTIVATION','RESET_PASSWORD','CHANGE_EMAIL_STEP1','CHANGE_EMAIL_STEP2') NOT NULL DEFAULT 'ACCOUNT_ACTIVATION'");
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('user')->tokenTable;
        $this->alterColumn($table, 'type', "ENUM('ACCOUNT_ACTIVATION','RESET_PASSWORD','CHANGE_EMAIL') NOT NULL DEFAULT 'ACCOUNT_ACTIVATION'");
    }
}
