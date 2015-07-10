<?php
use app\modules\user\Module;
use app\modules\post\models\Post;
use yii\helpers\StringHelper;
/* @var $this yii\web\View */
/* @var $post \app\modules\post\models\Post */
?>
<!-- content -->
<div class="content" style="margin: 0 auto;padding: 15px;font-family: Helvetica, Arial, sans-serif;max-width: 600px;display: block; direction: rtl;">
    <table style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;width: 100%; direction: rtl;">
        <tr style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif; direction: rtl;">
            <td style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif; direction: rtl;">
                <p class="lead" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 17px;line-height: 1.6; direction: rtl;">
                    <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url; ?>">
                        <?= $post->title; ?>
                    </a>
                    <small style="font-size: 14px;color: gray;">
                        <?= Module::t('mail','digest.by_cover.written_by').' '; ?>
                        <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername(); ?>">
                            <?= $post->user->getName();?>
                        </a>
                    </small>
                </p>
                <!-- A Real Hero (and a real human being) -->
                <p style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6; direction: rtl;">
                    <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url; ?>">
                        <img src="<?= Post::getCoverUrl($post->id) ?>" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;max-width: 100%;">
                    </a>
                </p><!-- /hero -->
                <p style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6; direction: rtl;">
                    <?= StringHelper::truncateWords($post->pure_text, 60); ?>
                </p>
            </td>
        </tr>
    </table>
</div><!-- /content -->