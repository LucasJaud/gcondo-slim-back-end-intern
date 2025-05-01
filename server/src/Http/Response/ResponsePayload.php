<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Http\HttpStatus;
use JsonSerializable;

class ResponsePayload implements JsonSerializable
{
    public function __construct(
        private HttpStatus $status = HttpStatus::OK,
        private array|object|null $data = null,
        private ?ResponseError $error = null
    ) {
    }

    public function status(): HttpStatus
    {
        return $this->status;
    }

    public function data(): array|object|null
    {
        return $this->data;
    }

    public function error(): ?ResponseError
    {
        return $this->error;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = ['statusCode' => $this->status->value];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->error !== null) {
            $payload['error'] = $this->error;
        }

        return $payload;
    }
}
