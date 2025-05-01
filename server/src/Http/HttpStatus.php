<?php

declare(strict_types=1);

namespace App\Http;

enum HttpStatus: int
{
    case OK = 200;
    case Created = 201;
    case BadRequest = 400;
    case InsufficientPrivileges = 403;
    case NotAllowed = 405;
    case NotImplemented = 501;
    case ResourceNotFound = 404;
    case ServerError = 500;
    case Unauthenticated = 401;
    case ValidationError = 422;
    case Conflict = 409;

    public function getReasonPhrase(): string
    {
        return match ($this) {
            self::OK => 'OK',
            self::Created => 'CREATED',
            self::BadRequest => 'BAD_REQUEST',
            self::InsufficientPrivileges => 'INSUFFICIENT_PRIVILEGES',
            self::NotAllowed => 'NOT_ALLOWED',
            self::NotImplemented => 'NOT_IMPLEMENTED',
            self::ResourceNotFound => 'RESOURCE_NOT_FOUND',
            self::ServerError => 'SERVER_ERROR',
            self::Unauthenticated => 'UNAUTHENTICATED',
            self::ValidationError => 'VALIDATION_ERROR',
            self::Conflict => 'CONFLICT',
        };
    }
}
