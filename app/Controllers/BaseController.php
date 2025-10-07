<?php

declare(strict_types=1);

namespace App\Controllers;

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


    public function validatePaginationParams(array $filters, $request) : bool {
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
            return true;
        }
        return false;
    }
}
