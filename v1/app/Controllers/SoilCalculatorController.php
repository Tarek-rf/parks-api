<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
// use Frostybee\Valicomb\Validator;
use App\Validation\Validator;




class SoilCalculatorController extends BaseController
{
    public function __construct() {}
    /**
     * Handles the post of getting the volume of soil needed. https://www.omnicalculator.com/biology/soil
     * @param Request $request The request
     * @param Response $response The response
     * @throws HttpBadRequestException If they are missing a parameter
     * @return Response The response with the data
     */
    function handleSoilCalculator(Request $request, Response $response): Response
    {

        $body = $request->getParsedBody();
        $rules = [
            "length" => [
                "required",
                "numeric",
                ["min", 0]
            ],
            "length_unit" => [
                "required",
                ['in', ['cm', 'm', 'ft'],'message' =>"Unit should be cm, m or ft and it should be lower case"]
            ],
            "width" => [
                "required",
                "numeric",
                ["min", 0]
            ],
            "width_unit" => [
                "required",
                ['in', ['cm', 'm', 'ft'],'message' =>"Unit should be cm, m or ft and it should be lower case"]
            ],
            "depth" => [
                "required",
                "numeric",
                ["min", 0]
            ],
            "depth_unit" => [
                "required",
                ['in', ['cm', 'm', 'ft'],'message' =>"Unit should be cm, m or ft and it should be lower case"]
            ]
        ];

        $validator = new Validator($body);
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            $errors = $validator->errorsToString();
            throw new HttpBadRequestException($request, $errors);
        }

        $length_unit = $body["length_unit"];
        $width_unit = $body["width_unit"];
        $depth_unit = $body["depth_unit"];

        $length = $body["length"];
        $width = $body["width"];
        $depth = $body["depth"];
        if ($length_unit === "m") {
            $length_meter = $length;
        } elseif ($length_unit === "cm") {
            $length_meter = $length / 100;
        } elseif ($length_unit === "ft") {
            $length_meter = $length * 0.3048;
        }

        if ($width_unit === "m") {
            $width_meter = $width;
        } elseif ($width_unit === "cm") {
            $width_meter = $width / 100;
        } elseif ($width_unit === "ft") {
            $width_meter = $width * 0.3048;
        }

        if ($depth_unit === "m") {
            $depth_meter = $depth;
        } elseif ($depth_unit === "cm") {
            $depth_meter = $depth / 100;
        } elseif ($depth_unit === "ft") {
            $depth_meter = $depth * 0.3048;
        }

        $result =  $length_meter * $width_meter * $depth_meter;
        $data = ["The volume of soil needed is {$result} m3"];

        return $this->renderJson($response, $data);
    }
}
