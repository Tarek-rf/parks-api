<?php

namespace App\Controllers;

use App\Domain\Models\HistoryModel;
use App\Domain\Services\HistoryService;
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
    public function __construct(private HistoryModel $history_model, private HistoryService $history_service) {

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

        $this->validateSortingParams($filters, $request,["age_of_park"]);

        $this->setPaginationParams($filters, $this->history_model, $request);

        $history = $this->history_model->getHistory($filters);

        return $this->renderJson($response, $history);
    }

    /**
     * handles the creation of a new history
     * @param $request the request send by the client to the server
     * @param $response the response sent by the server to the client
     * @return Response the response to send to the client which contains the payload
     */
    public function handleCreateHistory(Request $request, Response $response): Response
    {
        //* PROCESS A REQUEST FOR CREATING A NEW VENDOR
        // echo "QUACK!";

        //* 1. retrieve the received payload from the request.
        $data = $request->getParsedBody();
        // dd($data);
        $result = $this->history_service->doCreateHistory($data);

        if ($result->isSuccess()) {
            // 1) Prepare the json response.
            return $this->renderJson($response, $result->getData(), 201);
        }
        // the operation failed, return an error response.
        $payload = [
            "status" => "error",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 422);
    }

    /**
     * handles the updating of a existing history
     * @param $request the request send by the client to the server
     * @param $response the response sent by the server to the client
     * @return Response the response to send to the client which contains the payload
     */
    public function handleUpdateHistory(Request $request, Response $response, array $args): Response
    {
        //* PROCESS A REQUEST FOR CREATING A NEW VENDOR
        // echo "QUACK!";

        //* 1. retrieve the received payload from the request.
        $data = $request->getParsedBody();
        // dd($data);
        $result = $this->history_service->doUpdateHistory($data, ["id" => $args['id']]);

        if ($result->isSuccess()) {
            // 1) Prepare the json response.
            return $this->renderJson($response, $result->getData(), 200);
        }
        // the operation failed, return an error response.
        $payload = [
            "status" => "error",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 422);
    }

    /**
     * handles the deletion of a existing history
     * @param $request the request send by the client to the server
     * @param $response the response sent by the server to the client
     * @return Response the response to send to the client which contains the payload
     */
    public function handleDeleteHistory(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        // dd($data);
        $result = $this->history_service->doDeleteHistory($data);

        if ($result->isSuccess()) {
            // 1) Prepare the json response.
            return $this->renderJson($response, $result->getData(), 204);
        }
        // the operation failed, return an error response.
        $payload = [
            "status" => "error",
            "details" => $result->getErrors()
        ];

        return $this->renderJson($response, $payload, 422);

    }

}
