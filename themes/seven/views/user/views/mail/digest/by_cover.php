<?php
use app\modules\user\Module;
use app\modules\post\models\Post;
use yii\helpers\StringHelper;
/* @var $this yii\web\View */
/* @var $post \app\modules\post\models\Post */
?>
<!-- content -->
<div class="content" style="margin: 0 auto;padding: 15px;max-width: 600px;display: block; direction: rtl;">
    <table style="margin: 0;padding: 0;width: 100%; direction: rtl;">
        <tr style="margin: 0;padding: 0; direction: rtl;">
            <td style="margin: 0;padding: 0; direction: rtl;">
                <p style="font-family: Arial, Tahoma, Helvetica, sans-serif;font-size:21px;font-weight: bold;margin: 0;padding: 0;margin-bottom: 10px;line-height: 1.1; direction: rtl;">
                    <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url.'?utm_source=newsletter&utm_medium=header&utm_campaign=digest_by_cover'; ?>">
                        <?= $post->title; ?>
                    </a>
                    <small style="font-size: 11px;;color: gray;">
                        <?= Module::t('mail','digest.by_cover.written_by').' '; ?>
                        <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername(); ?>">
                            <?= $post->user->getName();?>
                        </a>
                    </small>
                </p>
                <!-- A Real Hero (and a real human being) -->
                <p style="margin: 0;padding: 0;margin-bottom: 10px;line-height: 1.6; direction: rtl;">
                    <a href="<?= Yii::getAlias('@homeUrl').'/'.$post->user->getUsername().'/'.$post->url.'?utm_source=newsletter&utm_medium=cover&utm_campaign=digest_by_cover'; ?>">
                        <img src="<?= Post::getCoverUrl($post->id) ?>" style="margin: 0;padding: 0;width: 600px; max-width: 600px;" width="600px;">
                    </a>
                </p><!-- /hero -->
                <p style="font-family: Arial, Tahoma, Helvetica, sans-serif;margin: 0;padding: 0;margin-bottom: 10px;line-height: 1.6; direction: rtl; text-align: justify">
                    <?= StringHelper::truncateWords($post->pure_text, 110); ?>
                </p>
            </td>
        </tr>
    </table>
</div><!-- /content -->