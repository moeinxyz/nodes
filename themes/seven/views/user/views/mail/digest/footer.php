<?php
use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $user \app\modules\user\models\User */
?>

<!-- FOOTER -->
</td>
<td style="margin: 0;padding: 0; direction: rtl;"></td>
</tr>
</table><!-- /BODY -->
<table class="footer-wrap" style="margin: 0;padding: 0;width: 100%;clear: both!important; direction: rtl;">
    <tr style="margin: 0;padding: 0; direction: rtl;">
        <td style="margin: 0;padding: 0; direction: rtl;"></td>
        <td class="container" style="margin: 0 auto!important;padding: 0;display: block!important;max-width: 600px!important;clear: both!important; direction: rtl;">

            <!-- content -->
            <div class="content" style="margin: 0 auto;padding: 15px;max-width: 600px;display: block; direction: rtl;">
                <table style="margin: 0;padding: 0;width: 100%; direction: rtl;">
                    <tr style="margin: 0;padding: 0; direction: rtl;">
                        <td align="center" style="margin: 0;padding: 0; direction: rtl;">
                            <p style="font-family: Arial, Tahoma, Helvetica, sans-serif;font-size: 13px;margin: 0;padding: 0;margin-bottom: 10px;line-height: 1.6; direction: rtl;">
                                <a href="<?= Yii::getAlias('@homeUrl'); ?>/user/setting" style="margin: 0;padding: 0;color: #2BA6CB;">
<?= Module::t('mail', 'digest.footer.setting') ?>
                                </a>
                                |
                                <a href="<?= Yii::getAlias('@homeUrl'); ?>" style="margin: 0;padding: 0;color: #2BA6CB;">
                                    <?= Module::t('mail', 'digest.footer.nodes') ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </div><!-- /content -->

        </td>
        <td style="margin: 0;padding: 0; direction: rtl;"></td>
    </tr>
</table><!-- /FOOTER -->

</body>
</html>