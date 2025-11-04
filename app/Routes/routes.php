<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Helpers\DateTimeHelper;
use App\Controllers\AnimalsController;
use App\Controllers\HistoryController;
use App\Controllers\LocationsController;
use App\Controllers\VegetationsController;
use App\Middleware\HelloMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token

    //* ROUTE: GET /
    // $app->add(HelloMiddleware::class);
    $app->get('/', [AboutController::class, 'handleAboutWebService']);





    // Locations Routes
    $app->get('/locations', [LocationsController::class, 'handleGetLocations']);

    $app->post('/locations', [LocationsController::class, 'handleCreateLocation']);

    $app->get('/locations/{id}', [LocationsController::class, 'handleGetLocationById']);


    $app->get('/animals', [AnimalsController::class, 'handleGetAnimals']);
    $app->post('/animals', [AnimalsController::class, 'handleCreateAnimal']);
    $app->delete('/animals', [AnimalsController::class, 'handleDeleteAnimal']);
    $app->put('/animals', [AnimalsController::class, 'handleUpdateAnimal']);


    $app->get('/vegetations', [VegetationsController::class, 'handleGetVegetations']);

    $app->post('/vegetations', [VegetationsController::class, 'handleCreateVegetation']);

    $app->get('/history', [HistoryController::class, 'handleGetHistory']);
    $app->post('/history', [HistoryController::class, 'handleCreateHistory']);
    $app->put('/history/{id}', [HistoryController::class, 'handleUpdateHistory']);
    $app->delete('/history/{id}', [HistoryController::class, 'handleDeleteHistory']);


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
