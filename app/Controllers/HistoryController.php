<?php

namespace App\Controllers;

use App\Domain\Models\HistoryModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * the history controller which uses the model to fetch history and make it a json response
 */
class HistoryController extends BaseController
{
    /**
     * creates an object of history controller
     * @param $history_model the model of the history class later used to fetch from the database
     */
    public function __construct(private HistoryModel $history_model) {

    }

    /**
     * Gets a collection resource of history
     * @param $request the request send by the client to the server
     * @param $response the response sent by the server to the client
     * @return Response the response to send to the client which contains the payload
     */
    public function handleGetHistory(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();

        $this->setPaginationParams($filters, $this->history_model, $request);

        $history = $this->history_model->getHistory($filters);

        return $this->renderJson($response, $history);
    }

}
