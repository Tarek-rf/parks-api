<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

/** @api */
class HttpResourceNotFoundException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Not Found.';

    protected string $title = '404 Bad Request';
    protected string $description = 'The Resource was not found';
}
