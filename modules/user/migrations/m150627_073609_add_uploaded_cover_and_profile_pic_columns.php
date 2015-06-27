<?php

use yii\db\Schema;
use yii\db\Migration;

class m150627_073609_add_uploaded_cover_and_profile_pic_columns extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'uploaded_profile_pic', 'ENUM("YES","NO") DEFAULT "NO" AFTER `profile_cover`');
        $this->addColumn($table, 'uploaded_cover_pic', 'ENUM("YES","NO") DEFAULT "NO" AFTER `uploaded_profile_pic`');
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'uploaded_cover_pic');
        $this->dropColumn($table, 'uploaded_profile_pic');
    }
}
