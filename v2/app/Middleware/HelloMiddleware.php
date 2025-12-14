<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Participant in processing a server request and response.
 *
 * An HTTP middleware component participates in processing an HTTP message:
 * by acting on the request, generating the response, or forwarding the
 * request to a subsequent middleware and possibly acting on its response.
 */
class HelloMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Optional: Handle the incoming request
        //* ...Before middleware!

        // $header = $request->getHeader("Accept");

        // foreach ($header as $key => $value) {
        //     if (!str_contains($value, "application/json")) {
        //         // You can throw an exception or return a response indicating unsupported media type
        //         throw new \App\Exceptions\HttpOutOfRangeInputException();
        //     }
        // }

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        // Optional: Handle the outgoing response
        //* ...After middleware!

        return $response;
    }
}
