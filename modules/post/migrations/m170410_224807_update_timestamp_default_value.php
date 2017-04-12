<?php

use yii\db\Migration;

class m170410_224807_update_timestamp_default_value extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'updated_at', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->alterColumn($table, 'published_at', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
        $this->alterColumn($table, 'score_updated_at', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
        $this->alterColumn($table, 'score_update_requested_at', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'updated_at', 'timestamp NOT NULL');
        $this->alterColumn($table, 'published_at', "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->alterColumn($table, 'score_updated_at', "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->alterColumn($table, 'score_update_requested_at', "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
    }
}
