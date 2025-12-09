<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\HttpInvalidSortingParamsException;
use App\Exceptions\HttpNonIntegerInputException;
use App\Exceptions\HttpNotAcceptableException;
use App\Exceptions\HttpOutOfRangeInputException;
use App\Validation\ValidationHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class DogBMIController extends BaseController
{
    public function __construct() {}

    /**
     * handles the post of getting the distance by between the postal codes and returns a response with the distance
     * @param Request $request the request
     * @param Response $response the response
     * @throws HttpBadRequestException if they are missing a parameter
     * @return Response the response with the data
     */
    public function handleCalculateDogBMI(Request $request, Response $response): Response
    {

        $body = $request->getParsedBody();
        if (!isset($body["height_unit"]) || !isset($body["height"]) || !isset($body["weight_unit"]) || !isset($body["weight"]) || empty($body["height_unit"]) || empty($body["height"]) || empty($body["weight_unit"]) || empty($body["weight"])) {
            throw new HttpBadRequestException($request, "One of the required body fields is missing");
        }


        if (in_array(strtolower($body["height_unit"]), ["in", "cm", "m"]) && in_array(strtolower($body["weight_unit"]), ["kg", "lb", "g"])) {

            $height = $body["height"];
            if ($body["height_unit"] === 'cm') {
                $height = $height / 2.54;
            } elseif ($body["height_unit"] === 'm') {
                $height = $height * 39.3701;
            }

            $weight = $body["weight"];
            if ($body["weight_unit"] === 'g') {
                $weight = $weight / 453.6;
            } elseif ($body["weight_unit"] === 'kg') {
                $weight = $weight * 2.205;
            }

            $data = ["BMI for the dog that weights " . $body["weight"] . $body["weight_unit"] . " and height of " . $body["height"] . $body["height_unit"] => round($weight / $height, 2)];

            return $this->renderJson($response, $data);
        }
        throw new HttpBadRequestException($request, "the unit of measurement is invalid: only kg, lb, g, in, cm, and m are supported");
    }
}
