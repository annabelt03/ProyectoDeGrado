<?php

return [

    'default' => 'default',

    'connections' => [

        'default' => [
            'host'       => env('MQTT_BROKER_HOST', '192.168.20.9'),
            'port'       => env('MQTT_BROKER_PORT', 1883),
            'username'   => env('MQTT_BROKER_USERNAME'),
            'password'   => env('MQTT_BROKER_PASSWORD'),
            'client_id'  => env('MQTT_CLIENT_ID', 'laravel-consumer'),
            'clean_session' => env('MQTT_CLEAN_SESSION', true),
            'protocol'   => 'mqtt',
            'tls' => [
                'enabled' => env('MQTT_TLS_ENABLED', false),
            ],
            'logging' => [
                'enabled' => true,
                'level'   => 'info',
            ],
            'auto_reconnect' => true,
            'reconnect_interval' => 3,
        ],

    ],

];
