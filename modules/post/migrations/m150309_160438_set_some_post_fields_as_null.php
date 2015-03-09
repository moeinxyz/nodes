<?php

use yii\db\Schema;
use yii\db\Migration;

class m150309_160438_set_some_post_fields_as_null extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'url', 'VARCHAR(256) DEFAULT NULL');
        $this->alterColumn($table, 'title', 'VARCHAR(256) DEFAULT NULL');
        $this->alterColumn($table, 'content', 'LONGTEXT DEFAULT NULL');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'url', 'VARCHAR(256) NOT NULL');
        $this->alterColumn($table, 'title', 'VARCHAR(256) NOT NULL');
        $this->alterColumn($table, 'content', 'LONGTEXT NOT NULL');
    }
}
