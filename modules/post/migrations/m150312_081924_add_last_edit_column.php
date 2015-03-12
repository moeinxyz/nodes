<?php

use yii\db\Schema;
use yii\db\Migration;

class m150312_081924_add_last_edit_column extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($table, 'last_update_type', 'ENUM("MANUAL","AUTOSAVE") NOT NULL DEFAULT "MANUAL" AFTER `updated_at`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($table, 'last_update_type');
    }
}
