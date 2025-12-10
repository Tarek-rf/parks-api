<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Validation\Validator;


class CoffeeCalculatorController extends BaseController
{
    public function __construct() {}
    /**
     *
     * Calculates the number of coffee portions allowed per day based on weight/kg, and tolerance
     *
     * @param Request $request
     * @param Response $response
     * @throws HttpBadRequestException
     * @return Response
     */
    function handleCoffeeCalculator(Request $request, Response $response): Response
    {

        $body = $request->getParsedBody();

        $rules = [
            "weightKg" => [
                'required',
                'numeric',
                ['min', 0]
            ],
            "sensitivity" => [
                'required',
                array('in', array('very_tolerant', 'more_tolerant', 'average', 'more_sensitive', 'very_sensitive'))
            ],
            "caffeinePer100g" => [
                'required',
                'numeric',
                ['min', 0]
            ],
            "portionSizeG" => [
                'numeric'
            ]

        ];

        $validator = new Validator($body);
        // dd($validator);
        // Important: map the validation rules before calling validate()
        $validator->mapFieldsRules($rules);

        if ($validator->validate()) {
        } else {
            $errors = $validator->errorsToString();
            throw new HttpBadRequestException($request, $errors);
        }


        $weightKg = $body['weightKg'];
        $sensitivity = strtolower($body['sensitivity']);
        $caffeinePer100g = $body['caffeinePer100g'];
        $portionSizeG = $body['portionSizeG'] ?? 240;

        $multipliers = [
            'very_tolerant' => 8,
            'more_tolerant' => 6,
            'average' => 4,
            'more_sensitive' => 3,
            'very_sensitive' => 2
        ];

        $multiplier = $multipliers[$sensitivity];
        // Calculate recommended daily caffeine (mg)
        $caffeineNeededMg = $weightKg * $multiplier;

        // Cap at safe maximum
        $caffeineNeededMg = min($caffeineNeededMg, 400);

        // Calculate caffeine per portion (mg)
        $portionSizeG = $portionSizeG ?? 100; // Default to full 100g/ml if not specified
        $caffeinePerPortionMg = ($portionSizeG / 100) * $caffeinePer100g;

        // Calculate number of portions
        // $portions[] = "Number of portions";
        $portions = ["Number of portions" => round($caffeineNeededMg / $caffeinePerPortionMg, 2)];


        return $this->renderJson($response, $portions);
    }
}
