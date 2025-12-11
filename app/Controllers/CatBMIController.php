<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class CatBMIController extends BaseController
{
    public function __construct() {}

    /**
     * Handles the post of getting the BMI of cat. https://www.omnicalculator.com/biology/cat-bmi
     * @param Request $request the request
     * @param Response $response the response
     * @throws HttpBadRequestException if they are missing a parameter
     * @return Response the response with the data
     */
    public function handleCalculateCatBMI(Request $request, Response $response): Response
    {

        $body = $request->getParsedBody();

        if (!isset($body["rib_cage_unit"]) || !isset($body["rib_cage"]) || !isset($body["rib_cage_unit"]) || !isset($body["rib_cage"]) || empty($body["leg_unit"]) || empty($body["leg"]) || empty($body["rib_cage_unit"]) || empty($body["rib_cage"])) {
            throw new HttpBadRequestException($request, "One of the required body fields is missing");
        }

        if (
            in_array(strtolower($body["leg_unit"]), ["in", "cm", "m", "ft", "mm"]) &&
            in_array(strtolower($body["rib_cage_unit"]), ["in", "cm", "m", "mm", "ft"])
        ) {

            $leg = $body["leg"];
            if ($body["leg_unit"] === 'in') {
                $leg = $leg * 2.54;
            } elseif ($body["leg_unit"] === 'm') {
                $leg = $leg * 100;
            } elseif ($body["leg_unit"] === 'ft') {
                $leg = $leg * 30.48;
            } elseif ($body["leg_unit"] === 'mm') {
                $leg = $leg / 10;
            }

            $rib_cage = $body["rib_cage"];
            if ($body["rib_cage_unit"] === 'in') {
                $rib_cage = $rib_cage * 2.54;
            } elseif ($body["rib_cage_unit"] === 'm') {
                $rib_cage = $rib_cage * 100;
            } elseif ($body["rib_cage_unit"] === 'ft') {
                $rib_cage = $rib_cage * 30.48;
            } elseif ($body["rib_cage_unit"] === 'mm') {
                $rib_cage = $rib_cage / 10;
            }

            $fbmi = (($rib_cage / 0.70622) - $leg / 0.9156) - $leg;
            $fbmi = round($fbmi, 1);

            $data = ["BMI for a cat that has a circumference " . $body["rib_cage"] . $body["rib_cage_unit"] . " around their rib cage and lower back leg that measures " . $body["leg"] . $body["leg_unit"] => $fbmi];
            return $this->renderJson($response, $data);
        }
        throw new HttpBadRequestException($request, "the unit of measurement is invalid: only in, cm, m, ft, mm are supported");
    }
}
