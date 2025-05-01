<?php

namespace App\Http\Error\Slim;

use Slim\Exception\HttpSpecializedException;

class HttpConflictException extends HttpSpecializedException
{
    /** @var int */
    protected $code = 409;

    /** @var string */
    protected $message = 'Conflict.';

    protected string $title = '400 Conflict';

    protected string $description = 'The request could not be completed due to a conflict with the current state of the target resource.';
}
