<?php

use yii\db\Schema;
use yii\db\Migration;

class m150720_001846_add_delete_to_comment_status extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->alterColumn($table, 'status', 'ENUM("PUBLISH","TRASH","USER_DELETE") DEFAULT "PUBLISH"');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->alterColumn($table, 'status', 'ENUM("PUBLISH","TRASH","ABUSE") DEFAULT "PUBLISH"');
    }
}
