<?php

return [
    /**
     * What is Zero Time?
     *
     * I used 0000-00-00 00:00:00 as base time for somewhere that I should use null to prevent null value in database,
     * But it's prohibited by mysql strict mode
     * So I assume my birthday as zero time for this project :)
     * Because it could't be exist before me
     */
    'zeroTime'              =>  '1992-03-22 00:00:00',
    
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
