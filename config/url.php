<?php
return [
            'showScriptName'    => false,
            'enablePrettyUrl'   => true,
                'rules' => [
                    // post module
                    '@<username:[\w\-]+>/<url:[\w\-]+>'                                     =>  'post/post/view',
                    '@<username:[\w\-]+>/<url:[\w\-]+>/comment/<timestamp:\d+>'             =>  'post/comment/write',
                    '@<username:[\w\-]+>'                                                   =>  'post/post/user',
                    '@<username:[\w\-]+>/rss'                                               =>  'post/post/rss',                    
                    'post/write'                                                            =>  'post/post/write',
                    'post/autosave/<id:\w+>'                                                =>  'post/post/autosave',
                    'post/edit'                                                             =>  'post/post/edit',
                    'post/publish'                                                          =>  'post/post/publish',
                    'post/upload'                                                           =>  'post/image/upload',
                    'post/preview'                                                          =>  'post/post/preview',
                    'post/admin'                                                            =>  'post/post/admin',
                    'post/comments'                                                         =>  'post/comment/comments',
                    'post/stat'                                                             =>  'post/stat/stat',
                    'post/trash'                                                            =>  'post/post/trash',
                    'post/delete'                                                           =>  'post/post/delete',
                    '@<username:[\w\-]+>/<url:[\w\-]+>/recommend'                           =>  'post/post/recommend',

                    // user module
                    'user/login'                                                            =>  'user/user/login',
                    'user/join'                                                             =>  'user/user/join',
                    'user/auth'                                                             =>  'user/user/auth',
                    'user/setting'                                                          =>  'user/user/setting',
                    'user/profile'                                                          =>  'user/user/profile',
                    'user/logout'                                                           =>  'user/user/logout',
                    'user/activation'                                                       =>  'user/user/activation',
                    'user/reset'                                                            =>  'user/user/reset',
                    'user/follow'                                                           =>  'user/following/follow',
                    
                    'token/change'                                                          =>  'user/token/change',
                    'token/activation'                                                      =>  'user/token/activation',
                    'token/reset'                                                           =>  'user/token/reset',
                    
                    // embed module
                    'embed'                                                                 =>  'embed/embed/embed',
                    
                    // social module
                    'social/admin'                                                          =>  'social/social/admin',
                    'social/auth'                                                           =>  'social/social/auth',
                ],
        ];