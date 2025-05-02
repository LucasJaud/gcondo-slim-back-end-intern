<?php

namespace App\Controllers;

use App\Http\HttpStatus;
use App\Http\Response\ResponseBuilder;
use App\Services\ReservationService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class ReservationController{

    public function __construct(private ReservationService $service){}

    public function list(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        $reservations = isset($params['venue_id'])
            ? $this->service->listByVenue($params['venue_id'])
            : $this->service->list();

        $data = ['reservations' => $reservations];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    }

    public function listByCondominium(Request $request, Response $response, array $args): Response
    {
        $condominiumId = (int)$args['id'];

        $data = ['reservations' => $this->service->listByCondominium($condominiumId)];

        return ResponseBuilder::respondWithData($response, data: $data);
    }

    public function find(Request $request, Response $response, array $args): Response
    {
        $data = ['reservation' => $this->service->find($args['id'])];

        $response = ResponseBuilder::respondWithData($response, data: $data);

        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $data = ['reservation' => $this->service->create($body)];

        $response = ResponseBuilder::respondWithData($response, HttpStatus::Created, $data);

        return $response;
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();

        $data = ['reservation' => $this->service->update($args['id'], $body)];

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

