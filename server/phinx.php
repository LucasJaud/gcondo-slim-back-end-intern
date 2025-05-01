<?php

use Dotenv\Dotenv;

// Required to production environment, but not to local development
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$dotenv->required([
    'DATABASE_DRIVER',
    'DATABASE_HOST',
    'DATABASE_PORT',
    'DATABASE_NAME',
    'DATABASE_USERNAME',
    'DATABASE_PASSWORD',
]);

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_environment' => 'default',
        'default' => [
            'adapter' => $_ENV['DATABASE_DRIVER'],
            'host' => $_ENV['DATABASE_HOST'],
            'port' => $_ENV['DATABASE_PORT'],
            'user' => $_ENV['DATABASE_USERNAME'],
            'pass' => $_ENV['DATABASE_PASSWORD'],
            'name' => $_ENV['DATABASE_NAME'],
            'charset' => 'utf8',
        ]
    ]
];
