<?php

namespace App\Http\Error\Slim;

use Slim\Exception\HttpSpecializedException;

class HttpUnprocessableEntityException extends HttpSpecializedException
{
    /** @var int */
    protected $code = 422;

    /** @var string */
    protected $message = 'Unprocessable entity.';

    protected string $title = '422 Unprocessable Entity';

    protected string $description = 'The request is well-structured but contains invalid data.';
}
