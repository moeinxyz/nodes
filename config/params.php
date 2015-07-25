<?php

return [
    'adminEmail'            =>  'moein7tl@gmail.com',
    'embedlyKeys'           =>  ['a06a9e786a354857962ab68ca89a2406'],
    'linkedinCallbackUrl'   =>  'http://nodes.ir/medium/web/social/auth?authclient=linkedin',
    'noreply-email'         =>  'noreply@nodes.ir',
    'twitterHandler'        =>  '@nodesir',
    'rabbitmq'              =>  [
        'server'                =>  'localhost',
        'port'                  =>  5672,
        'username'              =>  'guest',
        'password'              =>  'guest',
        'vhost'                 =>  '/',
    ],
    'wildcat_urls'  =>  [
        'posts',
        'recommends',
        'rss',
        'followers',
        'following',
    ],
    'notValidLoginRedirectPath' =>  [
        '/user/join',
        '/user/activation',
        '/user/reset'
    ],
    'validLogoutRedirectPath' => [
        '/@',
        '/page'
    ]
];
