<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpValidationException extends HttpSpecializedException
{
    protected $code = 422;
    protected $message = 'Validation failed';
    protected string $title = '422 Unprocessable Entity';
    protected string $description = 'Request data failed validation.';
}
