<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Helpers\DateTimeHelper;
use App\Controllers\AnimalsController;
use App\Controllers\HistoryController;
use App\Controllers\LocationsController;
use App\Controllers\UserAuthController;
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
    $app->delete('/locations', [LocationsController::class, 'handleDeleteLocation']);
    $app->put('/locations/{id}', [LocationsController::class, 'handleUpdateLocation']);
    $app->get('/locations/{id}', [LocationsController::class, 'handleGetLocationById']);

    // Animals Routs
    $app->get('/animals', [AnimalsController::class, 'handleGetAnimals']);
    $app->post('/animals', [AnimalsController::class, 'handleCreateAnimal']);
    $app->delete('/animals', [AnimalsController::class, 'handleDeleteAnimal']);
    $app->put('/animals/{id}', [AnimalsController::class, 'handleUpdateAnimal']);
    $app->get('/log', [AnimalsController::class, 'handleLogRequestInfo']);

    // Vegetations Routs
    $app->get('/vegetations', [VegetationsController::class, 'handleGetVegetations']);
    $app->post('/vegetations', [VegetationsController::class, 'handleCreateVegetation']);
    $app->delete('/vegetations', [VegetationsController::class, 'handleDeleteVegetation']);
    $app->put('/vegetations/{id}', [VegetationsController::class, 'handleUpdateVegetation']);

    // History Routs
    $app->get('/history', [HistoryController::class, 'handleGetHistory']);
    $app->post('/history', [HistoryController::class, 'handleCreateHistory']);
    $app->put('/history/{id}', [HistoryController::class, 'handleUpdateHistory']);
    $app->delete('/history', [HistoryController::class, 'handleDeleteHistory']);


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

    $app->post('/login', [UserAuthController::class, 'handleGenerateJwt']);
    $app->post('/register', [UserAuthController::class, 'handleRegisterUser']);

};
