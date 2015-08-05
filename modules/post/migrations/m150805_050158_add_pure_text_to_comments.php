<?php

use yii\db\Schema;
use yii\db\Migration;
use app\modules\post\models\Comment;
class m150805_050158_add_pure_text_to_comments extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->addColumn($table, 'pure_text', 'TEXT NOT NULL AFTER `text`');
        foreach (Comment::findAll([]) as $comment)
        {
            $comment->pure_text = strip_tags($comment->text);
            $comment->save();
        }
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->commentTable;
        $this->dropColumn($table, 'pure_text');
    }
}
