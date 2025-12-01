<?php

declare(strict_types=1);

use Slim\App;
use App\Middleware\ContentNegotiationMiddleware;
use App\Helpers\CustomErrorHandler;
use App\Middleware\AuthMiddleware;
use App\Middleware\MessageLogMiddleware;

return function (App $app) {
    //TODO: Add your middleware here.
    $app->add(ContentNegotiationMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    $app->add(MessageLogMiddleware::class);


    //!NOTE: the error handling middleware MUST be added last.
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    $callableResolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();

    $customErrorHandler = new CustomErrorHandler($callableResolver, $responseFactory);
    //!NOTE: You can add override the default error handler with your custom error handler.
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

    //* For more details, refer to Slim framework's documentation.
};
