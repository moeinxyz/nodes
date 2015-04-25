<?php

use yii\db\Schema;
use yii\db\Migration;

class m150328_165948_remove_delete_from_auth_column extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->alterColumn($table, 'auth', 'ENUM("AUTH", "DEAUTH") NOT NULL DEFAULT "AUTH"');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->alterColumn($table, 'auth', 'ENUM("AUTH", "DEAUTH","DELETE") NOT NULL DEFAULT "AUTH"');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
