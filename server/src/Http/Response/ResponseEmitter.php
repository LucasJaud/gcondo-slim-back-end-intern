<?php

declare(strict_types=1);

namespace App\Http\Response;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\ResponseEmitter as SlimResponseEmitter;

class ResponseEmitter extends SlimResponseEmitter
{
    /**
     * {@inheritdoc}
     */
    public function emit(Response $response): void
    {
        // This variable should be set to the allowed host from which your API can be accessed with
        $origin = $_ENV['CORS_ALLOWED_ORIGIN'];

        $response = $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        if (ob_get_contents()) {
            ob_clean();
        }

        parent::emit($response);
    }
}
