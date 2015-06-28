<?php

use yii\db\Schema;
use yii\db\Migration;

class m150628_230022_change_score_on_recommendation_post_user_guest extends Migration
{
    public function up()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->alterColumn($table, 'score', 'TINYINT UNSIGNED NOT NULL');

        $table      =   Yii::$app->getModule('post')->guestToReadTable;
        $this->alterColumn($table, 'score', 'TINYINT UNSIGNED NOT NULL');
    }

    public function down()
    {
        $table      =   Yii::$app->getModule('post')->userToReadTable;
        $this->alterColumn($table, 'score', 'TINYINT NOT NULL');

        $table      =   Yii::$app->getModule('post')->guestToReadTable;
        $this->alterColumn($table, 'score', 'TINYINT NOT NULL');    }
}
