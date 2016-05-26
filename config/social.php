<?php
return [
            'class' => 'yii\authclient\Collection',
            'clients'   =>  [
                'linkedin' => [
                    'class'         => 'yii\authclient\clients\LinkedIn',
                    'clientId'      => $_ENV['SOCIAL_LINKEDIN_KEY'],
                    'clientSecret'  => $_ENV['SOCIAL_LINKEDIN_SECRET'],
                    'scope'         =>  implode(' ', ['w_share'])
                ],                  
                'twitter' => [
                    'class'         => 'yii\authclient\clients\Twitter',
                    'consumerKey'   => $_ENV['SOCIAL_TWITTER_KEY'],
                    'consumerSecret'=> $_ENV['SOCIAL_TWITTER_SECRET'],
                ],
//                'facebook' => [
//                    'class'         => 'yii\authclient\clients\Facebook',
//                    'clientId'      => $_ENV['SOCIAL_FACEBOOK_KEY'],
//                    'clientSecret'  => $_ENV['SOCIAL_FACEBOOK_SECRET'],
//                    'scope'         =>  implode(' ',['publish_actions'])
//                ],                
            ] 
];