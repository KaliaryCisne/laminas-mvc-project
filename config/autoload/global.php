<?php

$db_name = $_ENV['DB_NAME'];
$db_host = $_ENV['DB_HOST'];
$db_port = $_ENV['DB_PORT'];

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => "pgsql:dbname={$db_name};host={$db_host};port={$db_port}",
    ],
    'service_manager' => [
        'factories' => [
            'Laminas\Db\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory',
        ],
    ],
];
