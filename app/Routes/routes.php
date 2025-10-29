<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Helpers\DateTimeHelper;
use App\Controllers\AnimalsController;
use App\Controllers\HistoryController;
use App\Controllers\VegetationsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token

    //* ROUTE: GET /
    $app->get('/', [AboutController::class, 'handleAboutWebService']);












    $app->get('/animals', [AnimalsController::class, 'handleGetAnimals']);

    $app->get('/vegetations', [VegetationsController::class, 'handleGetVegetations']);
    
    $app->post('/vegetations', [VegetationsController::class, 'handleCreateVegetation']);

    $app->get('/locations', [AnimalsController::class, 'handleGetLocations']);

    $app->get('/history', [HistoryController::class, 'handleGetHistory']);

    //* ROUTE: GET /ping
    $app->get('/ping', function (Request $request, Response $response, $args) {

        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    });
    // Example route to test error handling.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });
};
