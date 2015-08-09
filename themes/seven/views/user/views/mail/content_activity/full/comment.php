<?php
use app\modules\user\Module;
use Miladr\Jalali\jDateTime;
/* @var $this yii\web\View */
/* @var $comment \app\modules\post\models\comment */
?>
<!-- content -->
<div class="content" style="margin: 0 auto;padding: 15px;max-width: 600px;display: block; direction: rtl;">
    <table bgcolor="" style="margin: 0;padding: 0;width: 100%; direction: rtl;">
        <tr style="margin: 0;padding: 0; direction: rtl;">
            <td class="small" width="20%" style="vertical-align: top;padding-right: 10px;margin: 0;padding: 0; direction: rtl;">
                <a href="<?= Yii::getAlias('@homeUrl').'/'.$comment->user->getUsername().'?utm_source=notification&utm_medium=image&utm_campaign=full'; ?>">
                    <img src="<?= $comment->user->getProfilePicture(75) ?>" style="margin: 0;padding: 0;max-width: 100%;width: 75px;height: 75px; max-width: 75px;max-height: 75px;border-radius: 5px;" width="75px" height="75px">
                </a>                
            </td>
            <td style="margin: 0;padding: 0; direction: rtl;">
                <p style="font-family: Arial, Tahoma, Helvetica, sans-serif;font-size:21px;font-weight: bold;margin: 0;padding: 0;line-height: 1.1;margin-bottom: 15px;color: #000; direction: rtl;">
                    <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$comment->user->getUsername().'?utm_source=notification&utm_medium=header&utm_campaign=full'; ?>">
                        <?= $comment->user->getName();?>
                    </a>                                       
                    <small style="color: gray;font-size: 11px;">
                        <a style="text-decoration: none;" href="<?= Yii::getAlias('@homeUrl').'/'.$comment->post->user->getUsername().'/'.$comment->post->url; ?>?utm_source=notification&utm_medium=image&utm_campaign=digest#comment<?= md5($comment->id) ?>">
                            <?= jDateTime::date("l jS F Y",strtotime($comment->created_at))?>
                        </a>
                    </small>                    
                </p>
                <p class="" style="font-family: Arial, Tahoma, Helvetica, sans-serif;margin: 0;padding: 0;margin-bottom: 10px;line-height: 1.6; direction: rtl;text-align: justify;">
                    <?= $comment->pure_text;?>
                </p>
                <a href="<?= Yii::getAlias('@homeUrl').'/'.$comment->post->user->getUsername().'/'.$comment->post->url; ?>?utm_source=notification&utm_medium=button&utm_campaign=full#comment<?= md5($comment->id) ?>" class="btn" style="margin: 0;padding: 10px 16px;color: #ffffff;text-decoration: none;background-color: #5d9cec;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;">
                    <?= Module::t('mail','comment.full.btn') ?>
                </a>
            </td>
        </tr>
    </table>

</div><!-- /content -->