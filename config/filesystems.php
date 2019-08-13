<?php

return [

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'pbx' => [
            'driver' => 'sftp',
            'host' => env('PBX_SFTP_ADDRESS','20.20.20.182'),
            'username' => env('PBX_SFTP_USERNAME','root'),
            'password' => env('PBX_SFTP_PASSWORD',''),
            'port' => env('PBX_SFTP_PORT',22),
            'root' => '/var/lib/asterisk/sounds',
        ],

    ],

];
