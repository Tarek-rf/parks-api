<?php

namespace App\Controllers;

use App\Domain\Models\UserModel;
use App\Domain\Services\UserAuthService;
use App\Helpers\Core\AppSettings;
use App\Helpers\Core\PasswordTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Logger;
use App\Helpers\LogHelper;
use Firebase\JWT\JWT;
use Slim\Exception\HttpBadRequestException;

class UserAuthController extends BaseController
{
    use PasswordTrait;
    private UserModel $user_model;

    public function __construct(UserModel $user_model, private UserAuthService $user_auth_service, private AppSettings $app_settings)
    {
        $this->user_model = $user_model;
    }

    /**
     * registers a user account to the database
     * @param Request $request the request
     * @param Response $response the response
     * @return Response the response to return
     */
    public function handleRegisterUser(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $result = $this->user_auth_service->validateRegisterUser($data);

        if ($result->isSuccess()) {
            $data = $result->getData();
            return $this->renderJson($response, $data, 201);
        }

        //* The operation failed, return the error response

        $payload = [
            "code" => "error",
            "message" => "failed to register a new user, refer to the details below",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 400);
    }

    //* POST /token or /login
    /**
     * generates the JWT token
     * @param Request $request the request
     * @param Response $response the response
     * @throws HttpBadRequestException if it is a bad request
     * @return Response the response 
     */
    public function handleGenerateJwt(Request $request, Response $response): Response
    {
        // 1) get the user info from the request code

        $body = $request->getParsedBody();
        $email = $body["email"];

        $model = $this->user_model->getUser($email);
        //2) Verify the credentials -> password trait
        if (!$model) {
            throw new HttpBadRequestException($request, "the email is not found");
        }

        if (!$this->isPasswordValid($body["password"], $model["password"])) {
            throw new HttpBadRequestException($request, "the Password is not good");
        }
        //  use passwordtrait

        //3) Valid credentials -> generate a JWT token

        // 3 a) Generate a JWT Token

        $exp_time = time() + 3600; // expires in 1 hour
        $payload = [
            "iss" => "Parks API",
            "iat" => time(),
            "exp" => $exp_time,
            "aud" => "Anyone",
            "sub" => "whatever",
            //"nbf"=> "Your web service",
            //private claims
            "role" => $model["role"],
            "user_id" => $model["user_id"],
            "email" => $model["email"],


        ];

        //4) return a JSON response including a JWT token
        $secret = $this->app_settings->get('secret');


        $jwt = JWT::encode($payload, $secret, 'HS256');
        dd($jwt);
        $jwt_payload = [

            "status" => "success",
            "message" => "The JWT was generated successfully!",
            "jwt" => $jwt
        ];

        return $this->renderJson($response, $jwt_payload);
    }
}
