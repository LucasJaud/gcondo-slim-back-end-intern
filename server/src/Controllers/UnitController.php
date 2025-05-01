<?php

namespace App\Controllers;

use App\Http\HttpStatus;
use App\Http\Response\ResponseBuilder;
use App\Services\UnitService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UnitController
{
    public function __construct(private UnitService $service) {}

    public function list(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        $units = isset($params['condominium_id'])
            ? $this->service->listByCondominium($params['condominium_id'])
            : $this->service->list();

        $data = ['units' => $units];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    }

    public function find(Request $request, Response $response, array $args): Response
    {
        $data = ['unit' => $this->service->find($args['id'])];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $data = ['unit' => $this->service->create($body)];

        $response = ResponseBuilder::respondWithData($response, HttpStatus::Created, $data);

        return $response;
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $data = ['unit' => $this->service->update($args['id'], $body)];

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
