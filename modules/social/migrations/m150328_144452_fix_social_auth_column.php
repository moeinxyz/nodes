<?php

use yii\db\Schema;
use yii\db\Migration;

class m150328_144452_fix_social_auth_column extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->alterColumn($table, 'auth', 'ENUM("AUTH", "DEAUTH","DELETE") NOT NULL DEFAULT "AUTH"');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->alterColumn($table, 'auth', 'ENUM("AUTH", "DEAUTH","DELTED") NOT NULL DEFAULT "AUTH"');
    }
}
