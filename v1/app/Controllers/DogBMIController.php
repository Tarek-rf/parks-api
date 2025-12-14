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
use App\Validation\Validator;



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
        $rules = [
            "height_unit" => [
                "required",
                ['in', ['in', 'cm', 'm','IN', 'CM','M','iN', 'cM','In', 'Cm']]
            ],
            "height" => [
                "required",
                "numeric",
                ["min", 0]
            ],
            "weight_unit" => [
                "required",
                ['in', ['kg', 'lb', 'g', 'KG', 'LB', 'G', 'Kg', 'Lb', 'kG', 'lB',]]
            ],
            "weight" => [
                "required",
                "numeric",
                ["min", 0]
            ]
        ];

        $validator = new Validator($body);
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            $errors = $validator->errorsToString();
            throw new HttpBadRequestException($request, $errors);
        }



        $height = $body["height"];
        if (strtolower($body["height_unit"]) === 'cm') {
            $height = $height / 2.54;
        } elseif (strtolower($body["height_unit"]) === 'm') {
            $height = $height * 39.3701;
        }


        $weight = $body["weight"];
        if (strtolower($body["weight_unit"]) === 'g') {
            $weight = $weight / 453.6;
        } elseif (strtolower($body["weight_unit"]) === 'kg') {
            $weight = $weight * 2.205;
        }

        $data = ["BMI for the dog that weights " . $body["weight"] . $body["weight_unit"] ." and height of " . $body["height"] . $body["height_unit"] => round($weight/$height, 2)];

        return $this->renderJson($response, $data);

    }
}
