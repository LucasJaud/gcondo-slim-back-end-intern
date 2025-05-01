<?php

declare(strict_types=1);

namespace App\Http\Response;

use JsonSerializable;

class ResponseError implements JsonSerializable
{
    public function __construct(
        private string $type,
        private ?string $description = null
    ) {}

    public function type(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description = null): self
    {
        $this->description = $description;

        return $this;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'description' => $this->description,
        ];
    }
}
