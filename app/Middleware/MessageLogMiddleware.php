<?php

namespace App\Middleware;

use App\Domain\Models\LogModel;
use App\Exceptions\HttpNotAcceptableException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Helpers\LogHelper;


class MessageLogMiddleware implements MiddlewareInterface
{
    public function __construct(private LogModel $log_model)
    {}

    /**
     * Process an incoming server request and verify application/json media type.
     *
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
       $log_record = "{$request->getMethod()} {$request->getUri()->getPath()}";

        LogHelper::logAccess($log_record, $request->getQueryParams());

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        // Optional: Handle the outgoing response
        //* ...After middleware!
        // echo "after middleware";

        return $response;
    }
}
