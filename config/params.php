<?php

return [
    'adminEmail'            =>  $_ENV['ADMIN_EMAIL'],
    'embedlyKeys'           =>  [$_ENV['EMBEDLY_APIKEY']],
    'linkedinCallbackUrl'   =>  'http://nodes.ir/social/auth?authclient=linkedin',
    'noreply-email'         =>  'noreply@nodes.ir',
    'twitterHandler'        =>  '@nodesdotir',
    'rabbitmq'              =>  [
        'server'                =>  $_ENV['RABBITMQ_HOST'],
        'port'                  =>  $_ENV['RABBITMQ_PORT'],
        'username'              =>  $_ENV['RABBITMQ_USERNAME'],
        'password'              =>  $_ENV['RABBITMQ_PASSWORD'],
        'vhost'                 =>  $_ENV['RABBITMQ_VHOST'],
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
