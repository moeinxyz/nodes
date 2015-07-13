<?php
return [
    'app\\assets\\FontAwesomeAssets' =>  [
        'class'         =>  'app\\assets\\FontAwesomeAssets',
    ],
    'app\\assets\\InitAssets'  =>  [
        'class'         =>  'app\assets\InitAssets',        
        'depends'       =>  [
                        'yii\web\YiiAsset',
                        'yii\bootstrap\BootstrapPluginAsset',
                        'app\\assets\\FontAwesomeAssets'
                    ]        
    ],
    'app\\assets\\WowAssets'=>  [
        'class'         =>  'app\assets\WowAssets',
    ],    
    'app\\assets\\MainAssets'  =>  [
        'class'         =>  'app\\assets\\MainAssets',        
        'depends'       =>  [
            'app\\assets\\InitAssets',
            'app\\assets\\WowAssets'
        ]        
    ],
    'app\\assets\\ICheckAssets'    =>  [
        'class'         =>  'app\assets\ICheckAssets',
        'depends'       =>  [
            'app\\assets\\MainAssets'
        ]
    ],
    'app\\assets\\BootstrapSwitchAssets'  =>  [
        'class'         =>  'app\assets\BootstrapSwitchAssets',
        'depends'       =>  [
            'app\\assets\\MainAssets'
        ]        
    ],
    'app\\assets\\UnderscoreAssets'=>  [
        'class'         =>  'app\assets\UnderscoreAssets',
    ],
    'app\\assets\\SanitizeAssets'  =>  [
        'class'         =>  'app\assets\SanitizeAssets',
    ],
    'app\\assets\\DanteAssets'     =>  [
        'class'         =>  'app\assets\DanteAssets',
        'depends'       =>  [
            'yii\web\JqueryAsset',
            'app\\assets\\SanitizeAssets',
            'app\\assets\\UnderscoreAssets',
            'app\\assets\\MainAssets'
        ]
        
    ],
    'app\\assets\\CustomEditorAssets'     =>  [
        'class'         =>  'app\assets\CustomEditorAssets',
        'depends'       =>  [
            'app\\assets\\MainAssets',
            'app\\assets\\DanteAssets'
        ]
    ],
    'app\\assets\\ShowPostAssets'  =>  [
        'class'         => 'app\assets\ShowPostAssets',
        'depends'       =>  [
            'app\\assets\\MainAssets',
        ]
    ],
    'app\\assets\\JasnyBootstrapAssets'   =>  [
        'class'         =>  'app\assets\JasnyBootstrapAssets',
        'depends'       =>  [
            'app\\assets\\MainAssets',
        ]
    ],
];