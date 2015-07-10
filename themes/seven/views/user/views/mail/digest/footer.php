<?php
use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $user \app\modules\user\models\User */
?>

<!-- FOOTER -->
</td>
<td style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;"></td>
</tr>
</table><!-- /BODY -->
<table class="footer-wrap" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;width: 100%;clear: both!important;">
    <tr style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;">
        <td style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;"></td>
        <td class="container" style="margin: 0 auto!important;padding: 0;font-family: Helvetica, Arial, sans-serif;display: block!important;max-width: 600px!important;clear: both!important;">

            <!-- content -->
            <div class="content" style="margin: 0 auto;padding: 15px;font-family: Helvetica, Arial, sans-serif;max-width: 600px;display: block;">
                <table style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;width: 100%;">
                    <tr style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;">
                        <td align="center" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;">
                            <p style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
                                <a href="<?= Yii::getAlias('@homeUrl'); ?>/user/setting" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;color: #2BA6CB;">
<?= Module::t('mail', 'digest.footer.setting') ?>
                                </a>
                                |
                                <a href="<?= Yii::getAlias('@homeUrl'); ?>" style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;color: #2BA6CB;">
                                    <?= Module::t('mail', 'digest.footer.nodes') ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </div><!-- /content -->

        </td>
        <td style="margin: 0;padding: 0;font-family: Helvetica, Arial, sans-serif;"></td>
    </tr>
</table><!-- /FOOTER -->

</body>
</html>