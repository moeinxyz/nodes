<?php
return [
            'class' => 'yii\authclient\Collection',
            'clients'   =>  [
                'linkedin' => [
                    'class'         => 'yii\authclient\clients\LinkedIn',
                    'clientId'      => '78wy3unfyzmzd3',
                    'clientSecret'  => 'JUCwxoA39IygIk4L',
                    'scope'         =>  implode(' ', ['w_share'])
                ],                  
                'twitter' => [
                    'class'         => 'yii\authclient\clients\Twitter',
                    'consumerKey'   => 'yev9zPwjVEXZEr6t5hXAnTmsT',
                    'consumerSecret'=> 'ZPfzJJMDbj104KYy80ikoAjqOLIm41ECLogLvY9PfKjej4NLDg',
                ],
//                'facebook' => [
//                    'class'         => 'yii\authclient\clients\Facebook',
//                    'clientId'      => '512347738932737',
//                    'clientSecret'  => '6592c07e5de56bc3c9b4e5458b414581',
//                    'scope'         =>  implode(' ',['publish_actions'])
//                ],                
            ] 
];