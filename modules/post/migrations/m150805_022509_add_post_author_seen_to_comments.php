<?php

use yii\db\Schema;
use yii\db\Migration;

class m150805_022509_add_post_author_seen_to_comments extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->addColumn($table, 'post_author_seen', 'ENUM("SEEN","NOT_SEEN") DEFAULT "NOT_SEEN" AFTER `status`');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->dropColumn($table, 'post_author_seen');
    }
}
