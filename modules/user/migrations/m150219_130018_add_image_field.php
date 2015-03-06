<?php

use yii\db\Schema;
use yii\db\Migration;

class m150219_130018_add_image_field extends Migration
{
    public function up()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->addColumn($table, 'profile_pic', "ENUM('GRAVATAR','UPLOADED') NOT NULL DEFAULT 'GRAVATAR' AFTER `tagline`");
        $this->addColumn($table, 'profile_cover', "ENUM('NOCOVER','UPLOADED') NOT NULL DEFAULT 'NOCOVER' AFTER `profile_pic`");
    }

    public function down()
    {
        $table  =   Yii::$app->getModule('user')->userTable;
        $this->dropColumn($table, 'profile_pic');
        $this->dropColumn($table, 'profile_cover');
    }
}
