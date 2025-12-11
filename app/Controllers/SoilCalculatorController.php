<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;


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
        if (!isset($body["length"]) || !isset($body["width"]) || !isset($body["depth"]) || !isset($body["length_unit"]) ||  !isset($body["width_unit"]) || !isset($body["depth_unit"]) || empty($body["length"]) || empty($body["width"]) || empty($body["depth"]) || empty($body["length_unit"]) || empty($body["width_unit"]) || empty($body["depth_unit"])) {
            throw new HttpBadRequestException($request, "You might be missing one of these fields: length, width, depth, length_unit, width_unit, depth_unit. Or you might have wrote them incorrectly. Or you are wrapping it in array when it should be just json.");
        }

        if (!is_numeric($body["length"]) || !is_numeric($body["width"]) || !is_numeric($body["depth"])) {
            throw new HttpBadRequestException($request, "The fields length, width and depth should only be numbers.");
        }
        $correctUnits = ["m", "cm", "ft"];
        $length_unit = $body["length_unit"];
        $width_unit = $body["width_unit"];
        $depth_unit = $body["depth_unit"];

        if (in_array(strtolower($length_unit), $correctUnits) && in_array(strtolower($width_unit), $correctUnits) && in_array(strtolower($depth_unit), $correctUnits)) {

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
        throw new HttpBadRequestException($request, "Only m, cm and ft are acceptable as measure units");
    }
}
