<?php

$app = require __DIR__ . '/../src/bootstrap.php';

// We don't need HTTP responses in a CLI environment, such as CronJob
if (php_sapi_name() === 'cli') {
    return $app;
}

$serverRequestCreator = Slim\Factory\ServerRequestCreatorFactory::create();
$serverRequest = $serverRequestCreator->createServerRequestFromGlobals();

$response = $app->handle($serverRequest);

$responseEmitter = new App\Http\Response\ResponseEmitter();
$responseEmitter->emit($response);