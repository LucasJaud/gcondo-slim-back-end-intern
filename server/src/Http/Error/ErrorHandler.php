<?php

declare(strict_types=1);

namespace App\Http\Error;

use App\Http\Error\Slim\HttpConflictException as SlimHttpConflictException;
use App\Http\Error\Slim\HttpUnprocessableEntityException as SlimHttpUnprocessableEntityException;
use App\Http\HttpStatus;
use App\Http\Response\ResponseError;
use App\Http\Response\ResponsePayload;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpException as SlimHttpException;
use Slim\Exception\HttpNotFoundException as SlimHttpNotFoundException;
use Slim\Exception\HttpBadRequestException as SlimHttpBadRequestException;
use Slim\Exception\HttpForbiddenException as SlimHttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException as SlimHttpMethodNotAllowedException;
use Slim\Exception\HttpNotImplementedException as SlimHttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException as SlimHttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;
use PDOException;

class ErrorHandler extends SlimErrorHandler
{
    /** @inheritdoc */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $request = $this->request;

        $status = HttpStatus::ServerError;

        $error = new ResponseError(
            $status->getReasonPhrase(),
            'An internal error has occurred while processing your request.'
        );

       

        // Translates Domain Http exceptions to Slim Http exceptions
        if ($exception instanceof HttpException) {
            $exceptionName = match (true) {
                $exception instanceof HttpNotFoundException => SlimHttpNotFoundException::class,
                $exception instanceof HttpBadRequestException => SlimHttpBadRequestException::class,
                $exception instanceof HttpUnprocessableEntityException => SlimHttpUnprocessableEntityException::class,
                default => null
            };

            // Not implemented? Skip handling belows and go with original exception
            if ($exceptionName !== null)
                $exception = new $exceptionName($request, $exception->getMessage());
        }

        // Handles Slim Http exceptions (Either original or "translated" from Domain Http exceptions)
        if ($exception instanceof SlimHttpException) {
            $status = HttpStatus::from($exception->getCode());
            $error->setDescription($exception->getMessage());

            $httpStatus = match (true) {
                $exception instanceof SlimHttpNotFoundException => HttpStatus::ResourceNotFound,
                $exception instanceof SlimHttpMethodNotAllowedException => HttpStatus::NotAllowed,
                $exception instanceof SlimHttpUnauthorizedException => HttpStatus::Unauthenticated,
                $exception instanceof SlimHttpForbiddenException => HttpStatus::InsufficientPrivileges,
                $exception instanceof SlimHttpBadRequestException => HttpStatus::BadRequest,
                $exception instanceof SlimHttpNotImplementedException => HttpStatus::NotImplemented,
                $exception instanceof SlimHttpConflictException => HttpStatus::Conflict,
                $exception instanceof SlimHttpUnprocessableEntityException => HttpStatus::ValidationError,
                default => null
            };

            // Not implemented? Go with HttpStatus::ServerError->getReasonPhrase()
            if ($httpStatus !== null) {
                $type = $httpStatus->getReasonPhrase();
                $error->setType($type);
            }
        }

        // Handles non-Http exceptions
        if (
            !($exception instanceof SlimHttpException)
            && $exception instanceof Throwable
            && $this->displayErrorDetails
        ) {
            $error->setDescription($exception->getMessage());
        }

        // Handles PDO Exceptions
        if ($exception instanceof PDOException)
        {
            $errorMessage = $exception->getMessage();
            if (strpos($errorMessage, 'SQLSTATE[23000]') !== false && strpos($errorMessage, '1062 Duplicate entry') !== false){
                // Importante: Definir o status como Conflict (409)
                $status = HttpStatus::Conflict;
                
                // Importante: Definir o tipo de erro
                $error->setType('DUPLICATE_ENTRY');
                
                preg_match("/for key '(.*?)'/", $errorMessage, $matches);
                $fieldInfo = isset($matches[1]) ? $matches[1] : 'unknown field';

                preg_match("/Duplicate entry '(.*?)' for/", $errorMessage, $valueMatches);
                $duplicateValue = isset($valueMatches[1]) ? $valueMatches[1] : '';

                $field = $fieldInfo;
                if (preg_match('/\.([^.]+)$/', $fieldInfo, $fieldNameMatches)) {
                    $field = strtoupper($fieldNameMatches[1]);
                }

                if (strpos($fieldInfo, 'condominiums.url') !== false) {
                    $error->setDescription("Duplicate entry: The $field '$duplicateValue' already exists in our condominium database. Please provide a unique URL.");
                } else {
                    $error->setDescription("Duplicate entry: The $field '$duplicateValue' already exists in the system. Please provide a unique value.");
                }
            }
        }

        $payload = new ResponsePayload($status, null, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($status->value);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
         
    }
}
