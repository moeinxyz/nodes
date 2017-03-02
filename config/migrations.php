<?php
/**
 * Created by IntelliJ IDEA.
 * User: moein
 * Date: 2/28/17
 * Time: 11:33 PM
 */

return [
    'mongodb'       =>  [],
    'relational'    =>  [
        Yii::getAlias('@app').DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, ['modules', 'user', 'migrations']),
        Yii::getAlias('@app').DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, ['modules', 'post', 'migrations']),
        Yii::getAlias('@app').DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, ['modules', 'embed', 'migrations']),
        Yii::getAlias('@app').DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, ['modules', 'social', 'migrations']),
    ]
];