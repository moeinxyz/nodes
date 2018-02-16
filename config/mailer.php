<?php
/**
 * Created by IntelliJ IDEA.
 * User: moein
 * Date: 5/26/16
 * Time: 2:59 PM
 */

return [
    'class'     =>  'boundstate\mailgun\Mailer',
    'key'       =>  $_ENV['MAILER_APIKEY'],
    'domain'    =>  $_ENV['MAILER_DOMAIN'],
];