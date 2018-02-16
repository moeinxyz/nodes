<?php
use app\modules\user\Module;
use yii\helpers\StringHelper;
/* @var $this yii\web\View */
/* @var $post \app\modules\post\models\Post */
?>
<!-- content -->
<div class="content" style="margin: 0 auto;padding: 15px;max-width: 600px;display: block; direction: rtl;">
    <table bgcolor="" style="margin: 0;padding: 0;width: 100%; direction: rtl;">
        <tr style="margin: 0;padding: 0; direction: rtl;">
            <td class="small" width="20%" style="vertical-align: top;padding-right: 10px;margin: 0;padding: 0; direction: rtl;">
                <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername(); ?>">
                    <img src="<?= $post->user->getProfilePicture(75) ?>" style="margin: 0;padding: 0;max-width: 100%;width: 75px;height: 75px;border-radius: 50%;-moz-border-radius: 50%;-khtml-border-radius: 50%;-o-border-radius: 50%;-webkit-border-radius: 50%;-ms-border-radius: 50%; max-width: 75px;max-height: 75px;" width="75px" height="75px">
                </a>                
            </td>
            <td style="margin: 0;padding: 0; direction: rtl;">
                <p style="font-family: Arial, Tahoma, Helvetica, sans-serif;font-size:21px;font-weight: bold;margin: 0;padding: 0;line-height: 1.1;margin-bottom: 15px;color: #000; direction: rtl;">
                    <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url.'?utm_source=newsletter&utm_medium=header&utm_campaign=digest_no_cover'; ?>">
                        <?= $post->title; ?>
                    </a>
                    <small style="color: gray;font-size: 11px;">
                        <?= Module::t('mail','digest.no_cover.written_by').' '; ?>
                        <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername(); ?>">
                            <?= $post->user->getName();?>
                        </a>
                    </small>                    
                </p>
                <p class="" style="font-family: Arial, Tahoma, Helvetica, sans-serif;margin: 0;padding: 0;margin-bottom: 10px;line-height: 1.6; direction: rtl;text-align: justify;">
                    <?= StringHelper::truncateWords($post->pure_text, 90); ?>
                </p>
                <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url.'?utm_source=newsletter&utm_medium=btn&utm_campaign=digest_no_cover'; ?>" class="btn" style="margin: 0;padding: 10px 16px;color: #ffffff;text-decoration: none;background-color: #5d9cec;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;"><?= Module::t('mail','digest.no_cover.btn') ?></a>
            </td>
        </tr>
    </table>

</div><!-- /content -->