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

    /**
     *  Handles the animal collection and validates the sorting and pagination filters.
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @param \Psr\Http\Message\ResponseInterface $response Response
     * @return Response JSON response
     */
    public function handleGetAnimals(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();

        $this->validateSortingParams($filters, $request, ['common_name', 'average_weight_kg']);

        $this->setPaginationParams($filters, $this->animals_model, $request);

        $animals = $this->animals_model->getAnimals($filters);

        return $this->renderJson($response, $animals);
    }
}
