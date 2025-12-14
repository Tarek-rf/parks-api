<?php

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;



class ContentNegotiationMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and verify application/json media type.
     *
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Optional: Handle the incoming request
        //* ...Before middleware!
        $headerValueArray = $request->getHeader('Accept');

        if (!str_contains($headerValueArray[0], "application/json")) {

            throw new HttpNotAcceptableException($request, "Unsupported 'Accept' header: $headerValueArray[0]. Must accept 'application/json'.");
        }

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        // Optional: Handle the outgoing response
        //* ...After middleware!
        // echo "after middleware";

        return $response;
    }
}
