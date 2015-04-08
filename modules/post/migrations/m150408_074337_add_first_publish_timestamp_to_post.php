<?php

use yii\db\Schema;
use yii\db\Migration;

class m150408_074337_add_first_publish_timestamp_to_post extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($table, 'published_at', 'TIMESTAMP NOT NULL DEFAULT `0000-00-00 00:00:00` AFTER `updated_at`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($table, 'published_at');
    }
}
