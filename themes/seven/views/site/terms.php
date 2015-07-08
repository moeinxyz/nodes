<?php
$this->render('meta/_terms');
$this->title    =   Yii::t('app','terms.head.title');
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="top-buffer"></div>
        <div class="box box-info">
            <div class="box-header">
                <h1><?= Yii::t('app','terms.title');?></h1>
            </div>
            <div class="box-body">
                <ol>
                    <li><?= Yii::t('app','terms.body_p1'); ?></li>
                    <li><?= Yii::t('app','terms.body_p2'); ?></li>
                    <li><?= Yii::t('app','terms.body_p3'); ?></li>
                    <li><?= Yii::t('app','terms.body_p4'); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>