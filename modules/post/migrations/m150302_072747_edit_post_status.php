<?php

use yii\db\Schema;
use yii\db\Migration;

class m150302_072747_edit_post_status extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'status', 'ENUM("DRAFT","PUBLISH","TRASH","DELETE","WRITTING") DEFAULT "WRITTING"');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->postTable;
        $this->alterColumn($table, 'status', 'ENUM("DRAFT","PUBLISH","TRASH","DELETE")');
    }
}
