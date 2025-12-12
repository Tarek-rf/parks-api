<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Validation\Validator;

class CatBMIController extends BaseController
{
    public function __construct() {}

    /**
     * Handles the POST request for calculating Cat BMI.
     * https://www.omnicalculator.com/biology/cat-bmi
     *
     * @param Request $request
     * @param Response $response
     * @throws HttpBadRequestException Bad request
     * @return Response Response
     */
    public function handleCalculateCatBMI(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $rules = [
            "leg" => [
                "required",
                "numeric",
                ["min", 0]
            ],
            "leg_unit" => [
                "required",
                [
                    'in',
                    ['in', 'cm', 'm', 'ft', 'mm'],
                    'message' => "Unit must be one of: in, cm, m, ft, mm and lowercase"
                ]
            ],
            "rib_cage" => [
                "required",
                "numeric",
                ["min", 0]
            ],
            "rib_cage_unit" => [
                "required",
                [
                    'in',
                    ['in', 'cm', 'm', 'ft', 'mm'],
                    'message' => "Unit must be one of: in, cm, m, ft, mm and lowercase"
                ]
            ]
        ];

        $validator = new Validator($body);
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            $errors = $validator->errorsToString();
            throw new HttpBadRequestException($request, $errors);
        }

        $leg = $body["leg"];
        $leg_unit = strtolower($body["leg_unit"]);

        $rib_cage = $body["rib_cage"];
        $rib_cage_unit = strtolower($body["rib_cage_unit"]);

        switch ($leg_unit) {
            case "in":
                $leg *= 2.54;
                break;
            case "m":
                $leg *= 100;
                break;
            case "ft":
                $leg *= 30.48;
                break;
            case "mm":
                $leg /= 10;
                break;
        }

        switch ($rib_cage_unit) {
            case "in":
                $rib_cage *= 2.54;
                break;
            case "m":
                $rib_cage *= 100;
                break;
            case "ft":
                $rib_cage *= 30.48;
                break;
            case "mm":
                $rib_cage /= 10;
                break;
        }

        $fbmi = (($rib_cage / 0.70622) - ($leg / 0.9156)) - $leg;
        $fbmi = round($fbmi, 1);

        $data = [
            "Cat BMI" => $fbmi
        ];

        return $this->renderJson($response, $data);
    }
}
