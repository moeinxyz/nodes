<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

echo Html::button(
        'create',
        [
            'id' => 'modalButton'
 
        ]) ?>
 
 
    <?php
    Modal::begin([
            'id' => 'modal'
        ]);
 
    echo "<div id='modalContent'></div>";
 
    Modal::end();
    ?>