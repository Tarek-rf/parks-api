<?php

namespace App\Controllers;

use App\Domain\Models\LocationsModel;
use App\Exceptions\HttpValidationException;
use psr\Http\Message\ResponseInterface as Response;
use psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class LocationsController extends BaseController
{
    function __construct(private LocationsModel $locations_model) {}

    public function handleGetLocations(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();
        $page = isset($filters["page"]) && $filters["page"] > 0 ? (int)$filters["page"] : 1;
        $page_size = isset($filters["page_size"]) && $filters["page_size"] > 0 ? (int)$filters["page_size"] : 20;

        $this->locations_model->setPaginationOptions(
            $page,
            $page_size
        );
        $locations = $this->locations_model->getLocations($request, $filters);
        return $this->renderJson($response, $locations);
    }

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
}
