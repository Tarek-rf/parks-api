<?php

namespace App\Controllers;

use App\Domain\Models\LocationsModel;
use App\Domain\Services\LocationsService;
use App\Exceptions\HttpValidationException;
use psr\Http\Message\ResponseInterface as Response;
use psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use App\Helpers\WebserviceInvoker as Invoker;


class LocationsController extends BaseController
{
    function __construct(private LocationsModel $locations_model, private LocationsService $locations_service) {}

    /**
     *
     * Handles the GET request to fetch a list of locations.
     *
     * @param \psr\Http\Message\ServerRequestInterface $request
     * @param \psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function handleGetLocations(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();

        $this->validateSortingParams($filters, $request, ['id', 'name', 'country', 'area']);

        $this->setPaginationParams($filters, $this->locations_model, $request);

        $invoker = new Invoker([], $request);
        $locations["Observation Data"] = $invoker->invokeUri("https://api.gbif.org/v1/occurrence/search?country=US&hasCoordinate=true&basisOfRecord=HUMAN_OBSERVATION&limit=2")->results;

        $locations += $this->locations_model->getLocations($request, $filters);

        return $this->renderJson($response, $locations);
    }

    /**
     *
     * Handles the GET request to fetch a location by its ID.
     *
     * @param \psr\Http\Message\ServerRequestInterface $request
     * @param \psr\Http\Message\ResponseInterface $response
     * @param array $uri_args
     * @throws \App\Exceptions\HttpValidationException
     * @throws \Slim\Exception\HttpNotFoundException
     * @return Response
     */
    public function handleGetLocationById(Request $request, Response $response, array $uri_args): Response
    {
        $location_id = $uri_args["id"];
        if (!isset($location_id) || $location_id < 0 || !is_numeric($location_id)) {

            throw new HttpValidationException($request, "Invalid id value: Should be a positive integer and greater than zero.");
        }
        $location = $this->locations_model->getLocationById($location_id);
        if ($location === false) {
            throw new HttpNotFoundException($request, "There was no matching record");
        }
        return $this->renderJson($response, $location);
    }
    /**
     *
     * Handles the POST request to create a new location.
     *
     * @param \psr\Http\Message\ServerRequestInterface $request
     * @param \psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function handleCreateLocation(Request $request, Response $response): Response
    {
        //* 1) Retrieve the received payload from the request
        $new_location = $request->getParsedBody();
        // dd($data);
        $result = $this->locations_service->doCreateLocation($new_location);
        if ($result->isSuccess()) {
            //* 1) Prepare the JSON response.
            return $this->renderJson($response, $result->getData(), 201);
        }
        //* The operation failed. Return an error response
        $payload = [
            "status" => "error",
            "message" => "Failed to create the new location, refer to the details below",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 400);
    }
    /**
     *
     * Handles the DELETE request to delete one or more locations.
     *
     * @param \psr\Http\Message\ServerRequestInterface $request
     * @param \psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function handleDeleteLocation(Request $request, Response $response): Response
    {
        //* 1) Retrieve the location ID
        $location_ids = $request->getParsedBody();

        $result = $this->locations_service->doDeleteLocation($location_ids);
        if ($result->isSuccess()) {
            //* 1) Prepare the JSON response.
            return $this->renderJson($response, $result->getData(), 202);
        }
        //* The operation failed. Return an error response
        $payload = [
            "status" => "error",
            "message" => "Failed to delete the location, refer to the details below",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 400);
    }
    /**
     *
     * Handles the PUT request to update an existing location.
     *
     * @param \psr\Http\Message\ServerRequestInterface $request
     * @param \psr\Http\Message\ResponseInterface $response
     * @param array $uri_args
     * @return Response
     */
    public function handleUpdateLocation(Request $request, Response $response, array $uri_args): Response
    {
        //* Retrieve the received payload from the request
        $data = $request->getParsedBody();

        //* Retrieve the location ID
        $where_condition = ['id' => $uri_args['id']];

        $result = $this->locations_service->doUpdateLocation($data, $where_condition);
        if ($result->isSuccess()) {
            //* Prepare the JSON response.
            return $this->renderJson($response, $result->getData(), 202);
        }
        //* The operation failed. Return an error response
        $payload = [
            "status" => "error",
            "message" => "Failed to update the location, refer to the details below",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 400);
    }
}
