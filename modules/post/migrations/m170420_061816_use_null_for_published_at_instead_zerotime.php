<?php

use yii\db\Migration;

class m170420_061816_use_null_for_published_at_instead_zerotime extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'published_at', "TIMESTAMP NULL DEFAULT NULL");
        $this->alterColumn($table, 'score_updated_at', "TIMESTAMP NULL DEFAULT NULL");
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'published_at', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
        $this->alterColumn($table, 'score_updated_at', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
    }
}
