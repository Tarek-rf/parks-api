<?php

namespace App\Controllers;

use App\Domain\Models\AnimalsModel;
use App\Domain\Services\AnimalsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnimalsController extends BaseController
{
    private AnimalsModel $animals_model;

    public function __construct(AnimalsModel $animals_model, private AnimalsService $animals_service)
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

    public function handleCreateAnimal(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $result = $this->animals_service->doCreateAnimal($data);

        if ($result->isSuccess()) {
            $data["data"] = $result->getData();
            return $this->renderJson($response, $data, 201);
        }

        //* The operation failed, return the error response

        $payload = [
            "code" => "error",
            "message" => "failed to create the new animal, refer to the details below",
            "details" => $result->getErrors()
        ];


        return $this->renderJson($response, $payload, 400);
    }


    public function handleDeleteAnimal(Request $request, Response $response, array $uri_args): Response
    {
        $where_condition = ['id' => $uri_args['id']];

        $result = $this->animals_service->doDeleteAnimal($where_condition);

        if ($result->isSuccess()) {
            $data["data"] = $result->getData();
            return $this->renderJson($response, $data, 202);
        }

        $payload = [
            "status" => "error",
            "message" => "Failed to delete the location, refer to the details below",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 400);
    }

    public function handleUpdateAnimal(Request $request, Response $response, array $uri_args): Response
    {

        $data = $request->getParsedBody();

        $where_condition = ['id' => $uri_args['id']];

        $result = $this->animals_service->doUpdateAnimal($data, $where_condition);

        if ($result->isSuccess()) {
            $data = $result->getData();
            return $this->renderJson($response, $data, 202);
        }

        $payload = [
            "code" => "error",
            "message" => "Failed to update an animal, refer to the details below",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 400);
    }
}
