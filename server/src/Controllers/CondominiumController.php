<?php

namespace App\Controllers;

use App\Http\HttpStatus;
use App\Http\Response\ResponseBuilder;
use App\Services\CondominiumService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CondominiumController
{
    public function __construct(private CondominiumService $service) {}

    public function list(Request $request, Response $response): Response
    {
        $data = ['condominiums' => $this->service->list()];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    }


    public function find(Request $request, Response $response, array $args): Response
    {
        $data = ['condominium' => $this->service->find($args['id'])];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $data = ['condominium' => $this->service->create($body)];

        $response = ResponseBuilder::respondWithData($response, HttpStatus::Created, $data);

        return $response;
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $data = ['condominium' => $this->service->update($args['id'], $body)];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $deleted = $this->service->delete($args['id']);

        $status = $deleted ? HttpStatus::OK : HttpStatus::ServerError;

        $response = ResponseBuilder::respondWithData($response, $status);

        return $response;
    }
}
