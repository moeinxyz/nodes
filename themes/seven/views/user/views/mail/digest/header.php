<?php

use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $user \app\modules\user\models\User */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="margin: 0;padding: 0;" dir="rtl">
    <head style="margin: 0;padding: 0;">
        <!-- If you delete this meta tag, the ground will open and swallow you. -->
        <meta name="viewport" content="width=device-width" style="margin: 0;padding: 0;">

            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" style="margin: 0;padding: 0;">

                <link rel="stylesheet" type="text/css" href="stylesheets/email.css" style="margin: 0;padding: 0;">
                    <style type="text/css" style="margin: 0;padding: 0;">
                        @media only screen and (max-width: 600px) {

                            a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}

                            div[class="column"] { width: auto!important; float:none!important;}

                            table.social div[class="column"] {
                                width:auto!important;
                            }
                        }
                    </style>

                    </head>

                    <body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="margin: 0;padding: 0;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;height: 100%;direction: rtl;width: 100%!important;direction: rtl;">
                        <!-- BODY -->                        
                        <table class="body-wrap" bgcolor="" style="width:600px;margin-top:0;margin-right:auto;margin-bottom:0;margin-left:auto;padding-top:40px;padding-right:20px;padding-bottom:40px;padding-left:20px;color:#333332;line-height:1.4; direction: rtl;-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; " dir="rtl">
                            <tr style="margin: 0;padding: 0; direction: rtl;">
                                <td style="margin: 0;padding: 0; direction: rtl;"></td>
                                <td class="container" align="" bgcolor="#FFFFFF" style="margin: 0 auto!important;padding: 0;display: block!important;max-width: 600px!important;clear: both!important; direction: rtl;">

                                    <!-- content -->
                                    <div class="content" style="margin: 0 auto;padding: 15px;max-width: 600px;display: block; direction: rtl;">
                                        <table style="margin: 0;padding: 0;width: 100%; direction: rtl;">
                                            <tr style="margin: 0;padding: 0; direction: rtl;">
                                                <td style="margin: 0;padding: 0; direction: rtl;text-align: center">
                                                    <p style="font-family: Arial, Tahoma, Helvetica, sans-serif;font-size: 16px;margin: 0;padding: 0;line-height: 1.1;margin-bottom: 15px;color: #000; direction: rtl;">
                                                        <?= Module::t('mail', 'digest.header.hello_n_miss', ['name' => $user->getName()]); ?>
                                                    </p>
                                                    <p style="font-family: Arial, Tahoma, Helvetica, sans-serif;font-size: 13px;margin: 0;padding: 0;line-height: 1.1;margin-bottom: 15px;color: #000; direction: rtl;">
                                                        <?= Module::t('mail', 'digest.header.customized'); ?>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- /content -->

