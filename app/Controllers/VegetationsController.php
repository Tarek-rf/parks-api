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
    public function __construct(private VegetationsModel $vegetations_model, private VegetationsService $vegetations_service)
    {
    }

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

     public function handleCreateVegetation(Request $request, Response $response): Response {
        //*process a request for creating a new vendor
        echo "Quack!";
        //* 1) Retrieve the received payload from the request.
        $new_vegetation = $request->getParsedBody();
        // dd($data);
        $result= $this->vegetations_service->doCreateVegetation($new_vegetation);
        if ($result->isSuccess()){
            // 1) Prepare the JSON response.
            // $data["data"] = $result->getData();
            return $this->renderJson($response, $result->getData(),201);
        }
        //* The operation failed, return an error response.
        $payload=[
            "status"=>"error",
            "message"=> "Failed to create the new vendor, refer to the details below",
            "details"=> $result->getErrors()
        ];
        return $this->renderJson($response, $payload, 400);
    }

}
