<?php

return [
    'driver' => $_ENV['DATABASE_DRIVER'],
    'host' => $_ENV['DATABASE_HOST'],
    'database' => $_ENV['DATABASE_NAME'],
    'username' => $_ENV['DATABASE_USERNAME'],
    'password' => $_ENV['DATABASE_PASSWORD'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
];
