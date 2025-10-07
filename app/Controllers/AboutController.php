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

        $data = array(
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'Welcome! This is a Web service that provides information about national parks all around the country. It gives you information about the parks, the animals, the vegetations, the activities and so much more',
            'authors' => array(
               'Manal Jkini','Tarek Rafea', 'Andrew Shahini', 'Matthew Veroutis'
            ),
            'resources' => array(
                'animals'=> array(
                    'uri'=> '/animals',
                    'about'=>'This will provide the animals information like name, height, weight etc...'
                ),
                'vegetations'=>array(
                     'uri'=> '/vegetations',
                    'about'=>'This will provide the vegetations information like name, growth rate, preferable climate etc...'
                ),
                'locations'=>array(
                     'uri'=> '/locations',
                    'about'=>'This will provide the locations information like name of the park, country, area etc...'
                ),
                'history'=>array(
                     'uri'=> '/history',
                    'about'=>'This will provide the history information like founder, founded date, age of park etc...'
                )
            )
        );

        return $this->renderJson($response, $data);
    }
}
