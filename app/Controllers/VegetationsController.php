<?php

namespace App\Controllers;

use App\Domain\Models\VegetationsModel;
use App\Domain\Services\VegetationsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VegetationsController extends BaseController
{

    /**
     * Creates an object of vegetations controller
     * @param $vegetations_model the model of the vegetations class later used to fetch from the database
     */
    public function __construct(private VegetationsModel $vegetations_model, private VegetationsService $vegetations_service) {}

    /**
     * Get a collection resource of vegetations
     * @param $request the request sent by the client
     * @param  $response the response sent by the server to the client
     * @return Response the response sent to the the client
     */
    public function handleGetVegetations(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();

        $this->validateSortingParams($filters, $request, ["species_name"]);

        $this->setPaginationParams($filters, $this->vegetations_model, $request);

        $vegetations = $this->vegetations_model->getVegetations($filters);

        return $this->renderJson($response, $vegetations);
    }

     /**
     * Handles the creation of a new vegetation
     * @param $request the request send by the client to the server
     * @param $response the response sent by the server to the client
     * @return Response the response to send to the client which contains the payload
     */
    public function handleCreateVegetation(Request $request, Response $response): Response
    {
        //*process a request for creating a new vendor
        //* 1) Retrieve the received payload from the request.
        $new_vegetation = $request->getParsedBody();
        // dd($data);
        $result = $this->vegetations_service->doCreateVegetation($new_vegetation);
        if ($result->isSuccess()) {
            // 1) Prepare the JSON response.
            return $this->renderJson($response, $result->getData(), 201);
        }
        //* The operation failed, return an error response.
        $payload = [
            "status" => "error",
            "message" => "Failed to create the new vegetation, refer to the details below",
            "details" => $result->getErrors()
        ];
        return $this->renderJson($response, $payload, 400);
    }

     /**
     * Handles the update of an existing vegetation
     * @param $request the request send by the client to the server
     * @param $response the response sent by the server to the client
     * @param $args parameter that contains the vegetation id
     * @return Response the response to send to the client which contains the payload
     */
    public function handleUpdateVegetation(Request $request, Response $response, array $args): Response
    {
        //* 1) Retrieve the received payload from the request.
        $existing_vegetation = $request->getParsedBody();
        // dd($data);
        $result = $this->vegetations_service->doUpdateVegetation(["id" => $args['id']], $existing_vegetation);
        if ($result->isSuccess()) {
            // 1) Prepare the JSON response.
            return $this->renderJson($response, $result->getData(), 200);
        }
        //* The operation failed, return an error response.
        $payload = [
            "status" => "error",
            "message" => "Failed to update the new vegetation, refer to the details below",
            "details" => $result->getErrors()
        ];
        return $this->renderJson($response, $payload, 400);
    }

     /**
     * Handles the deleteion of an existing vegetation
     * @param $request the request send by the client to the server
     * @param $response the response sent by the server to the client
     * @return Response the response to send to the client which contains the payload
     */
    public function handleDeleteVegetation(Request $request, Response $response): Response
    {
        //* 1) Retrieve the received payload from the request.
        $delete = $request->getParsedBody();
        $result = $this->vegetations_service->doDeleteVegetation($delete);

        if ($result->isSuccess()) {
            // 1) Prepare the JSON response.
            return $this->renderJson($response, $result->getData(), 200);
        }
        //* The operation failed, return an error response.
        $payload = [
            "status" => "error",
            "message" => "Failed to delete the vegetation, refer to the details below",
            "details" => $result->getErrors()
        ];
        // dd($response);
        return $this->renderJson($response, $payload, 400);
    }
}
