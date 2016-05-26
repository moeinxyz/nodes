<?php
/**
 * Created by IntelliJ IDEA.
 * User: moein
 * Date: 5/26/16
 * Time: 3:02 PM
 */

return [
    'google' => [
        'class'         => 'yii\authclient\clients\GoogleOAuth',
        'clientId'      => $_ENV['AUTH_GOOGLE_KEY'],
        'clientSecret'  => $_ENV['AUTH_GOOGLE_SECRET'],
        'scope'         =>  implode(' ', ['email'])
    ],
    'github' => [
        'class'         => 'yii\authclient\clients\GitHub',
        'clientId'      => $_ENV['AUTH_GITHUB_KEY'],
        'clientSecret'  => $_ENV['AUTH_GITHUB_SECRET'],
        'scope'         =>  implode(' ', ['user:email'])
    ],
    'linkedin' => [
        'class'         => 'yii\authclient\clients\LinkedIn',
        'clientId'      => $_ENV['AUTH_LINKEDIN_KEY'],
        'clientSecret'  => $_ENV['AUTH_LINKEDIN_SECRET'],
    ],
    'facebook' => [
        'class'         => 'yii\authclient\clients\Facebook',
        'clientId'      => $_ENV['AUTH_FACEBOOK_KEY'],
        'clientSecret'  => $_ENV['AUTH_FACEBOOK_SECRET'],
    ],
];