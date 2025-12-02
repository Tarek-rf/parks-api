<?php

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use App\Helpers\Core\AppSettings;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use LogicException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use stdClass;
use UnexpectedValueException;

class AuthMiddleware implements MiddlewareInterface
{

    public function __construct(private AppSettings $app_settings) {}
    /**
     * Process an incoming server request and verify application/json media type.
     *
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Optional: Handle the incoming request
        //* ...Before middleware!
        $headerValueArray = $request->getHeader('Authorization');

        $jwt = substr($headerValueArray[0], 7);

        $keys = $this->app_settings->get('secret');

        // var_dump($keys);
        // dd($keys);
        try {

            $decoded = (array) JWT::decode($jwt, new Key($keys, 'HS256'));
            // dd("da fuaw");
            // $decoded = json_decode(json_encode($decoded), true);
            //var_dump($decoded['role']);
            $role = $decoded['role'];

            //get the method of request
            $method = $request->getMethod();
            //check role if user tries to do POST, PUT, DELETE throw an error
            //if()
            if ($role != 'admin' && $method != 'GET') {
                throw new HttpForbiddenException($request, "You shall not pass MALAKA!");
            }

            $request = $request->withAttribute('email', $decoded["email"]);
            // dd($request);
            $request = $request->withAttribute('user_id', $decoded["user_id"]);
        } catch (LogicException $e) {
            throw new HttpBadRequestException($request, "LogicException");
        } catch (UnexpectedValueException $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
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
