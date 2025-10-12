<?php

namespace App\Controllers;

use App\Domain\Models\AnimalsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnimalsController extends BaseController
{
    private AnimalsModel $animals_model;

    public function __construct(AnimalsModel $animals_model)
    {
        $this->animals_model = $animals_model;
    }

    public function handleGetAnimals(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();

        $this->setPaginationParams($filters, $this->animals_model, $request);

        //todo error handling and validation

        $animals = $this->animals_model->getAnimals($filters);

        return $this->renderJson($response, $animals);
    }
}
