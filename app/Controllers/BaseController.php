<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\HttpInvalidSortingParamsException;
use App\Exceptions\HttpNonIntegerInputException;
use App\Exceptions\HttpOutOfRangeInputException;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ResponseInterface as Response;


abstract class BaseController
{

    public function __construct() {}
    protected function renderJson(Response $response, array $data, int $status_code = 200): Response
    {
        // var_dump($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES |    JSON_PARTIAL_OUTPUT_ON_ERROR);
        //-- Write JSON data into the response's body.
        $response->getBody()->write($payload);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    /**
     * sets the pagination parameters if they are set and valid
     * @param array $filters the pagination parameters in an array
     * @param mixed $model the model used to set the pagination parameters
     * @return void it dose not return anything
     */
    protected function setPaginationParams(array $filters, $model, $request) : void {
        if($this->validatePaginationParams($filters, $request))
        {
            $model->setPaginationOptions(
                (int)$filters["page"],
                (int)$filters["page_size"],
            );
        }
    }

    /**
     * validates the Pagination parameters that are passed by the client
     * @param array $filters the filters which contain the pagination
     * @param mixed $request the request made by the client
     * @throws \App\Exceptions\HttpNonIntegerInputException if the input is not an integer
     * @throws \App\Exceptions\HttpOutOfRangeInputException if the input is out of range
     * @return void dose not return anything
     */
    public function validatePaginationParams(array $filters, $request) : void {
        if(isset($filters["page"]) && isset($filters["page_size"]))
        {
            if(!ValidationHelper::isInt($filters["page"]))
            {
                throw new HttpNonIntegerInputException($request, "Invalid page Param: must be a integer value");
            }
            if(!ValidationHelper::isInt($filters["page_size"])) {
                throw new HttpNonIntegerInputException($request, "Invalid page_size Param: must be a integer value");
            }

            if(!ValidationHelper::isIntAndInRange($filters["page"],1,1000))
            {
                throw new HttpOutOfRangeInputException($request, "Invalid page Param: must be a value between 1 and 1000");
            }
            if(!ValidationHelper::isIntAndInRange($filters["page_size"],1,1000)) {
                throw new HttpOutOfRangeInputException($request, "Invalid page_size Param: must be a value between 1 and 1000");
            }
        }
    }

    /**
     * validates the sorting params that where passed by the client
     * @param array $filters the filters which contain the sorting param
     * @param mixed $request the request sent by the client
     * @param array $allowedSortByParam the sorting param that is supported by the controller
     * @throws \App\Exceptions\HttpInvalidSortingParamsException if the sorting option is not supported
     * @return void dose not return anything
     */
    public function validateSortingParams(array $filters, $request, array $allowedSortByParam) : void {
        if(isset($filters["sort_by"]) && !empty($filters["sort_by"]) && isset($filters["order"]) && !empty($filters["order"]))
        {
            if(!in_array($filters["sort_by"], $allowedSortByParam))
            {
                throw new HttpInvalidSortingParamsException($request, "Invalid sorting Param: sort_by param must be supported by the resource");
            }
            if(!in_array(strtoupper($filters["order"]), ["ASC","DESC"])) {
                throw new HttpInvalidSortingParamsException($request, "Invalid sorting Param: order param must be ASC or DESC");
            }
        }
    }
}
