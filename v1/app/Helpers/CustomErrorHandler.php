<?php

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler;
use Throwable;

class CustomErrorHandler extends ErrorHandler
{

    /**
     * Logs detailed information about an error for debugging and monitoring purposes.
     *
     * This function is intended to be used within the error handling mechanism
     * to capture and store relevant error details, such as stack traces or
     * contextual information, to assist in diagnosing issues.
     *
     * @protected
     * @returns {void} This function does not return a value.
     */
    protected function logErrorDetails(): void
    {
        $exception = $this->exception;
        $request = $this->request;
        // Log the exception to a custom log file or third-party service
        // For example, use Monolog or any other logger you prefer.

        //TODO: Use your LogHelper class to log the error details.

        //

        //NOTE: The exception message and the stack trace should be included in the log record along with the request details.

        // Hints, you can use the following methods:
        $log_record = $exception->getMessage();
        $extraArray[0] = $exception->getTraceAsString();


        LogHelper::logError($log_record,$extraArray);

    }

    /**
     * Custom error handler for JSON responses.
     *
     * @param Throwable $exception
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function respond(): ResponseInterface
    {
        $statusCode = 400;
        $exception = $this->exception;

        // Customize response, e.g., returning JSON format for API
        $statusCode = $exception instanceof HttpException ? $exception->getCode() : 500;
        if ($exception instanceof HttpException) {
            $this->logErrorDetails();
        }

        // Create structured response payload.
        $data = [
            'status' => 'error',
            'code' => $statusCode,
            'type' => $this->getClassName($exception),
            'message' => $exception->getMessage()
        ];

        return $this->getErrorResponse($data, $statusCode);
    }

    private function getClassName($object)
    {
        $path = explode('\\', get_class($object));
        return array_pop($path);
    }

    private function getErrorResponse($data, $statusCode = 400)
    {
        // Create a response object.
        $response = $this->responseFactory->createResponse($statusCode)->withHeader("Content-type", "application/json");

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

        // Prepare a JSON response with an error message
        $response->getBody()->write($payload);
        return $response->withStatus($statusCode)->withHeader('Content-Type', 'application/json');
    }
}
