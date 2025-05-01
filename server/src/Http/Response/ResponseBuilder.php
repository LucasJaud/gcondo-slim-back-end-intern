<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Http\HttpStatus;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseBuilder
{
    public static function respondWithData(
        Response $response,
        HttpStatus $status = HttpStatus::OK,
        array|object|null $data = null
    ): Response {
        $payload = new ResponsePayload($status, $data);

        $json = json_encode($payload, JSON_PRETTY_PRINT);

        $response->getBody()->write($json);
        $response->withHeader('Content-Type', 'application/json');
        $response->withStatus($payload->status()->value);

        return $response;
    }
}
