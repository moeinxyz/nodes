<?php

use yii\db\Migration;

class m170421_154520_add_predictionio_status_fields extends Migration
{
    public function up()
    {
        $post          =   Yii::$app->getModule('post')->postTable;
        $this->addColumn($post, 'predictionio_status','ENUM("NEW", "SENT") NOT NULL DEFAULT "NEW" AFTER `score_update_requested_at`');

        $userRead      =   Yii::$app->getModule('post')->userReadTable;
        $this->addColumn($userRead, 'predictionio_status','ENUM("NEW", "SENT") NOT NULL DEFAULT "NEW"');

        $guestRead      =   Yii::$app->getModule('post')->guestReadTable;
        $this->addColumn($guestRead, 'predictionio_status','ENUM("NEW", "SENT") NOT NULL DEFAULT "NEW"');

        $userRecommend      =   Yii::$app->getModule('post')->userRecommendTable;
        $this->addColumn($userRecommend, 'predictionio_status','ENUM("NEW", "SENT") NOT NULL DEFAULT "NEW"');

    }

    public function down()
    {
        $post      =   Yii::$app->getModule('post')->postTable;
        $this->dropColumn($post, 'predictionio_status');

        $userRead      =   Yii::$app->getModule('post')->userReadTable;
        $this->dropColumn($userRead, 'predictionio_status');

        $guestRead      =   Yii::$app->getModule('post')->guestReadTable;
        $this->dropColumn($guestRead, 'predictionio_status');

        $userRecommend      =   Yii::$app->getModule('post')->userRecommendTable;
        $this->dropColumn($userRecommend, 'predictionio_status');
    }
}
