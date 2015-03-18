<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\post\models\Comment */

$this->title = Yii::t('comment', 'Update {modelClass}: ', [
    'modelClass' => 'Comment',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('comment', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('comment', 'Update');
?>
<div class="comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
