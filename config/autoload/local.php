<?php

$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

return [
    'db' => [
        'username' => $user,
        'password' => $pass
    ],
];
