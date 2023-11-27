<?php

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => "pgsql:dbname=db_test;host=localhost;port=5499",
    ],
    'service_manager' => [
        'factories' => [
            'Laminas\Db\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory',
        ],
    ],
];
