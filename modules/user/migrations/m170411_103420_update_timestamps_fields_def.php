<?php

use yii\db\Migration;

class m170411_103420_update_timestamps_fields_def extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('user')->userTable;
        $this->alterColumn($table, 'last_post_suggestion', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
        $this->alterColumn($table, 'last_digest_mail', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
        $this->alterColumn($table, 'last_content_activity_mail', "TIMESTAMP NOT NULL DEFAULT '".Yii::$app->params['zeroTime']."'");
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('user')->userTable;
        $this->alterColumn($table, 'last_post_suggestion', "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->alterColumn($table, 'last_digest_mail', "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->alterColumn($table, 'last_content_activity_mail', "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
    }
}
