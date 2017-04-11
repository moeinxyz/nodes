<?php

use yii\db\Migration;

class m170410_225913_update_timestamp_default_value extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->alterColumn($table, 'last_used', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('social')->socialTable;
        $this->alterColumn($table, 'last_used', 'timestamp NOT NULL');
    }
}
