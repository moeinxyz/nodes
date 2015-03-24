<?php

use yii\db\Schema;
use yii\db\Migration;

class m150324_214323_remove_abuse_from_comment_status extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->alterColumn($table, 'status', 'ENUM("PUBLISH","TRASH") DEFAULT "PUBLISH"');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->alterColumn($table, 'status', 'ENUM("PUBLISH","TRASH","ABUSE") DEFAULT "PUBLISH"');
    }
}
