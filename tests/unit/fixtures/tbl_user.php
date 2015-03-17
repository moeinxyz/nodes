<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'username'          =>  $faker->username,
    'email'             =>  $faker->email,
    'password'          =>  '$2y$13$KbcjUUgseFYYj99MRiXV.uETbRKeGbKyr4vGpgdEvWifMxazqwzh6',//Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'auth_key'          =>  Yii::$app->getSecurity()->generateRandomString(),
    'type'              =>  'USER',
    'status'            =>  'ACTIVE',
    'name'              =>  $faker->name,
    'tagline'           =>  $faker->sentence(2, true),
    'profile_pic'       =>  'GRAVATAR',
    'profile_cover'     =>  'NOCOVER',
    'content_activity'  =>  'OFF',
    'publisher_activity'=>  'OFF',
    'social_activity'   =>  'OFF',
    'reading_list'      =>  'OFF',
    'followers_count'   =>  0,
    'following_count'   =>  0,
    'posts_count'       =>  0,
    'recommended_count' =>  0,
    'notifications_count'=> 0,
];