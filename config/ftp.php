<?php
return [
            'class' => 'creocoder\flysystem\FtpFilesystem',
            'host'  => $_ENV['FTP_HOST'],
            'port'  => $_ENV['FTP_PORT'],
            'username' => $_ENV['FTP_USERNAME'],
            'password' => $_ENV['FTP_PASSWORD'],
            // 'root' => '/path/to/root',
            // 'passive' => false,
            // 'ssl' => true,
            // 'timeout' => 60,
            // 'permPrivate' => 0700,
            // 'permPublic' => 0744,
            // 'transferMode' => FTP_TEXT,
        ];
