<?php
// src/routes.php

use Slim\App;
use App\Controllers\WeatherController;
use App\Controllers\UserLocationController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
    // Trasa główna GET /
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Witaj w aplikacji Weatherly!");
        return $response->withHeader('Content-Type', 'text/plain');
    });

    // Trasa GET /api/weather obsługiwana przez WeatherController
    $app->get('/api/weather', [WeatherController::class, 'getWeather']);

    // Trasa GET /api/user-location obsługiwana przez UserLocationController
    $app->get('/api/user-location', [UserLocationController::class, 'getUserLocation']);

};