<?php

use yii\db\Schema;
use yii\db\Migration;

class m150407_133533_add_last_used_field_to_social extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->addColumn($table, 'last_used', 'timestamp NOT NULL AFTER `created_at`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->dropColumn($table, 'last_used');
    }
}
