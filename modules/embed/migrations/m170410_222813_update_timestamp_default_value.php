<?php

use yii\db\Migration;

class m170410_222813_update_timestamp_default_value extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('embed')->embedTable;
        $this->alterColumn($table, 'updated_at', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('embed')->embedTable;
        $this->alterColumn($table, 'updated_at', 'timestamp NOT NULL');
    }
}
