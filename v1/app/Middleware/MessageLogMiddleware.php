<?php

namespace App\Middleware;

use App\Domain\Models\LogModel;
use App\Exceptions\HttpNotAcceptableException;
use App\Helpers\Core\AppSettings;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Helpers\LogHelper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MessageLogMiddleware implements MiddlewareInterface
{
    public function __construct(private LogModel $log_model, private AppSettings $app_settings)
    {}

    /**
     * Process an incoming server request and verify application/json media type.
     *
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
       $log_record = "{$request->getMethod()} {$request->getUri()->getPath()}";
       LogHelper::logAccess($log_record, $request->getQueryParams());
        $email = $request->getAttribute("email") ? $request->getAttribute("email") : "malaka@gmail.com";
        $user_id = $request->getAttribute("user_id") ? $request->getAttribute("user_id") : "7";

        $this->log_model->createLog([
            'email' => $email,
            "user_action" => "{$request->getMethod()} {$request->getUri()->getPath()}",
            "logged_at" => DateTimeHelper::nowForDb(),
            "user_id" => $user_id
        ]);
        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        // Optional: Handle the outgoing response
        //* ...After middleware!
        // echo "after middleware";

        return $response;
    }
}
