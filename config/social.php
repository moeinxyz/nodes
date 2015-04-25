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
//                'twitter' => [
//                    'class'         => 'yii\authclient\clients\Twitter',
//                    'consumerKey'   => 'yev9zPwjVEXZEr6t5hXAnTmsT',
//                    'consumerSecret'=> 'ZPfzJJMDbj104KYy80ikoAjqOLIm41ECLogLvY9PfKjej4NLDg',
//                ],
//                'facebook' => [
//                    'class'         => 'yii\authclient\clients\Facebook',
//                    'clientId'      => '865054846839734',
//                    'clientSecret'  => '737f7715218838239a00a06aed234c13',
//                ],                
            ] 
];