<?php
use app\modules\user\Module;
use yii\helpers\StringHelper;
/* @var $this yii\web\View */
/* @var $post \app\modules\post\models\Post */
?>
<!-- content -->
<div class="content" style="margin: 0 auto;padding: 15px;font-family: Helvetica, Arial, sans-serif;max-width: 600px;display: block;">
    <table bgcolor="" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;width: 100%;">
        <tr style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;">
            <td class="small" width="20%" style="vertical-align: top;padding-right: 10px;margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;">
                <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername(); ?>">
                    <img src="<?= $post->user->getProfilePicture(75) ?>" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;max-width: 100%;">
                </a>                
            </td>
            <td style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;">
                <h4 style='margin: 0;padding: 0;font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 500;font-size: 23px;'>
                    <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url; ?>"><?= $post->title; ?></a>
                    <small>
                        <?= Module::t('mail','digest.no_cover.written_by').' '; ?>
                        <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername(); ?>">
                            <?= $post->user->getName();?>
                        </a>
                    </small>                    
                </h4>
                <p class="" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                    <?= StringHelper::truncateWords($post->pure_text, 60); ?>
                </p>
                <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url; ?>" class="btn" style="margin: 0;padding: 10px 16px;font-family: Helvetica, Arial, sans-serif;color: #ffffff;text-decoration: none;background-color: #5d9cec;font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;"><?= Module::t('mail','digest.no_cover.btn') ?></a>
            </td>
        </tr>
    </table>

</div><!-- /content -->