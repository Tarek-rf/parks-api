<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use Slim\Exception\HttpBadRequestException;
use Psr\Http\Server\RequestHandlerInterface as Request;

/**
 * Application settings utility class.
 *
 * Provides access to application configuration settings stored in an array.
 * @author  frostybee
 */
class WebserviceInvoker
{

    private $request_options;

    public function __construct(array $options, private $request)
    {
        $this->request_options = $options;
    }

    public function invokeUri(string $resourceUri): mixed
    {
        $client = new Client();
        // $response = $client->get($resourceUri, $this->request_options);
        try {
            $response = $client->get($resourceUri, $this->request_options);
        } catch (TransferException $ex) {
            //
            // - Log error/exception details.
            // - Terminate the execution of the current script.
            echo $ex->getMessage();
            if ($ex instanceof ConnectException) {
                throw new Exception($ex->getMessage());

            }
        }
        // Validate the response!
        //* 1) Check the status code! 200 OK!
        if ($response->getStatusCode() !== 200) {
            // Early return! Terminate the execution of the script.
            throw new HttpBadRequestException($this->request, "\something went wrong");
        }
        //* 2) Retrieve the received data from the response  body.
        $payload = $response->getBody()->getContents();
        // echo $payload;
        //* 3) Decode the JSON string representation of the list of leagues.
        $jsonObj = json_decode($payload);

        //* 4) Check if there was errors raised while decoding the JSON string.
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Early return.
            throw new Exception("Error Processing Request", 1);

        }
        // dd($jsonObj->data);
        return $jsonObj;
    }
}
