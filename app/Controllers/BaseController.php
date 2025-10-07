<?php

declare(strict_types=1);

namespace App\Controllers;

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
    protected function setPaginationParams(array $filters, $model) : void {
        if(isset($filters["page"]) && ValidationHelper::isIntAndInRange($filters["page"],1,1000) && isset($filters["page_size"]) && ValidationHelper::isIntAndInRange($filters["page_size"],1,50))
        {
            $model->setPaginationOptions(
                (int)$filters["page"],
                (int)$filters["page_size"],
            );
        }
    }
}
