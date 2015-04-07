<?php
return [
            'class' => 'yii\authclient\Collection',
            'clients'   =>  [
                'linkedin' => [
                    'class'         => 'yii\authclient\clients\LinkedIn',
                    'clientId'      => '78wy3unfyzmzd3',
                    'clientSecret'  => 'JUCwxoA39IygIk4L',
                    'scope'         =>  implode(' ', ['w_share'])
                    //93410b54-3f08-4313-9785-4018fc4c5a65 OAuth User Token:
                    //6f6c4d5f-3d2e-4c34-91eb-4745f56c1bb4 OAuth User Secret:
                ],                  
            ] 
];