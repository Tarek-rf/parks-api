<?php

namespace App\Controllers;

use App\Domain\Models\VegetationsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VegetationsController extends BaseController
{
     private VegetationsModel $vegetations_model;

    /**
     * Creates an object of vegetations controller
     * @param $vegetations_model the model of the vegetations class later used to fetch from the database
     */
    public function __construct(VegetationsModel $vegetations_model)
    {
        // $this->vegetations_model = $vegetations_model;
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
        //TODO merge with mathew to get the pagination method
        //Todo validation of pagination params
        // $this->setPaginationParams($filters, $this->vegetations_model);

        //Todo validate the filters
        //TODO errors

        $animals = $this->vegetations_model->getVegetations($filters);

        return $this->renderJson($response, $animals);
    }

    //TODO implement handleGetVegetationsById
}
