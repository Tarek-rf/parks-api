<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    private const API_NAME = 'parks-api';

    private const API_VERSION = '1.0.0';

    public function handleAboutWebService(Request $request, Response $response): Response
    {
        $baseUri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/parks-api';
        $data = array(
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'Welcome! This is a Web service that provides information about national parks all around the country. It gives you information about the parks, the animals, the vegetations, the activities and so much more',
            'authors' => array(
                'Manal Jkini',
                'Tarek Rafea',
                'Andrew Shahini',
                'Matthew Veroutis'
            ),
            'resources' => array(
                'animals' => array(
                    'uri' => $baseUri . '/animals',
                    'about' => 'This will provide the animals information like name, height, weight etc...',
                    'methods' => array(
                        'GET' => 'Fetches the list of Animals',
                        'POST' => 'Creates a new Animal',
                        'PUT' => 'Update an Animal',
                        'DELETE' => 'Deletes an Animal'
                    )
                ),
                'vegetations' => array(
                    'uri' => $baseUri . '/vegetations',
                    'about' => 'This will provide the vegetations information like name, growth rate, preferable climate etc...',
                    'methods' => array(
                        'GET' => 'Fetches the list of vegetations',
                        'POST' => 'Creates a new Vegetation',
                        'PUT' => 'Update a Vegetation',
                        'DELETE' => 'Deletes a Vegetation'
                    )
                ),
                'locations' => array(
                    'uri' => $baseUri . '/locations',
                    'about' => 'This will provide the locations information like name of the park, country, area etc...',
                    'methods' => array(
                        'GET' => 'Fetches the list of Locations',
                        'POST' => 'Creates a new Location',
                        'PUT' => 'Update a Location',
                        'DELETE' => 'Deletes a Location'
                    )
                ),
                'history' => array(
                    'uri' => $baseUri . '/history',
                    'about' => 'This will provide the history information like founder, founded date, age of park etc...',
                    'methods' => array(
                        'GET' => 'Fetches the list of History',
                        'POST' => 'Creates a new History',
                        'PUT' => 'Update a History',
                        'DELETE' => 'Deletes a History'
                    )
                )
            )
        );

        return $this->renderJson($response, $data);
    }
}
