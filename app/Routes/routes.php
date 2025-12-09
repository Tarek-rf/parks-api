
<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Helpers\DateTimeHelper;
use App\Controllers\AnimalsController;
use App\Controllers\DogBMIController;
use App\Controllers\CatBMIController;
use App\Controllers\HistoryController;
use App\Controllers\LocationsController;
use App\Controllers\SoilCalculatorController;
use App\Controllers\UserAuthController;
use App\Controllers\VegetationsController;
use App\Middleware\AuthMiddleware;
use App\Middleware\HelloMiddleware;
use App\Middleware\MessageLogMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return static function (Slim\App $app): void {

    // Routes without authentication check: /login, /token

    //* ROUTE: GET /

    $app->group("", function ($group) {
        // $app->add(HelloMiddleware::class);
        $group->get('/', [AboutController::class, 'handleAboutWebService']);

        // Locations Routes
        $group->get('/locations', [LocationsController::class, 'handleGetLocations']);
        $group->post('/locations', [LocationsController::class, 'handleCreateLocation']);
        $group->delete('/locations', [LocationsController::class, 'handleDeleteLocation']);
        $group->put('/locations/{id}', [LocationsController::class, 'handleUpdateLocation']);
        $group->get('/locations/{id}', [LocationsController::class, 'handleGetLocationById']);

        // Animals Routs
        $group->get('/animals', [AnimalsController::class, 'handleGetAnimals']);
        $group->post('/animals', [AnimalsController::class, 'handleCreateAnimal']);
        $group->delete('/animals', [AnimalsController::class, 'handleDeleteAnimal']);
        $group->put('/animals/{id}', [AnimalsController::class, 'handleUpdateAnimal']);
        $group->get('/log', [AnimalsController::class, 'handleLogRequestInfo']);

        // Vegetations Routs
        $group->get('/vegetations', [VegetationsController::class, 'handleGetVegetations']);
        $group->post('/vegetations', [VegetationsController::class, 'handleCreateVegetation']);
        $group->delete('/vegetations', [VegetationsController::class, 'handleDeleteVegetation']);
        $group->put('/vegetations/{id}', [VegetationsController::class, 'handleUpdateVegetation']);

        // History Routs
        $group->get('/history', [HistoryController::class, 'handleGetHistory']);
        $group->post('/history', [HistoryController::class, 'handleCreateHistory']);
        $group->put('/history/{id}', [HistoryController::class, 'handleUpdateHistory']);
        $group->delete('/history', [HistoryController::class, 'handleDeleteHistory']);
    })
        // ->add(MessageLogMiddleware::class)->add(AuthMiddleware::class)
    ; //comment this out for assign2


    $app->post('/dogBMI', [DogBMIController::class, 'handleCalculateDogBMI']);
    $app->post('/catBMI', [CatBMIController::class, 'handleCalculateCatBMI']);

    $app->post('/soil', [SoilCalculatorController::class, 'handleSoilCalculator']);

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

    $app->post('/login', [UserAuthController::class, 'handleGenerateJwt'])->add(MessageLogMiddleware::class);
    $app->post('/register', [UserAuthController::class, 'handleRegisterUser'])->add(MessageLogMiddleware::class);
};
