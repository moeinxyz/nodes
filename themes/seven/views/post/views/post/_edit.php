<?php
use yii\helpers\Html;
use app\modules\post\Module;
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */

app\assets\CustomEditorAssets::register($this);
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">    
        <div class="row">
            <div class="col-md-12">
                <br><br>
                <div id="editor">
                <?php
                    if ($type === 'auto'){
                        echo $model->autosave_content;
                    } else if ($model->content != NULL){
                        $title   = ($model->title == NULL)?Module::t('post','write.title.placeholder'):$model->title;
                        $content = "<h3 class=\"graf graf--h3 graf--first\">{$title}<br></h3>";
                        $content .= $model->content;
                        echo $content;
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('dynamicJS/_edit',['model'=>$model]);