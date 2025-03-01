<?php
// backend/src/Controllers/UserLocationController.php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Client;

class UserLocationController
{
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

  public function getUserLocation(Request $request, Response $response, array $args): Response
{
    // Pobranie adresu IP użytkownika
    $serverParams = $request->getServerParams();
    if (isset($serverParams['HTTP_X_FORWARDED_FOR'])) {
        // X-Forwarded-For może zawierać wiele adresów IP
        $ipAddress = trim(explode(',', $serverParams['HTTP_X_FORWARDED_FOR'])[0]);
    } elseif (isset($serverParams['HTTP_X_REAL_IP'])) {
        $ipAddress = $serverParams['HTTP_X_REAL_IP'];
    } elseif (isset($serverParams['REMOTE_ADDR'])) {
        $ipAddress = $serverParams['REMOTE_ADDR'];
    } else {
        $ipAddress = '0.0.0.0';
    }
    error_log("User IP Address: " . $ipAddress);

    try {
        // Wykonanie zapytania do API geolokalizacyjnego
        $apiResponse = $this->httpClient->get("https://ipapi.co/{$ipAddress}/json/", [
            'timeout' => 5,
        ]);
        $locationData = json_decode($apiResponse->getBody(), true);
        error_log("Geolocation API response: " . print_r($locationData, true));

        // Walidacja odpowiedzi
        if (isset($locationData['latitude']) && isset($locationData['longitude'])) {
            $coordinates = [
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
            ];
        } else {
            // Fallback do domyślnych koordynatów, jeśli odpowiedź jest nieprawidłowa
            $coordinates = [
                'latitude' => 51.505, // Domyślna szerokość geograficzna (np. Londyn)
                'longitude' => -0.09,  // Domyślna długość geograficzna (np. Londyn)
            ];
        }

        // Zapisanie koordynatów do odpowiedzi
        $response->getBody()->write(json_encode($coordinates));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\Exception $e) {
        // Logowanie błędu
        error_log("Geolocation API Error: " . $e->getMessage());

        // Fallback do domyślnych koordynatów w przypadku błędu
        $coordinates = [
            'latitude' => 51.505, // Domyślna szerokość geograficzna
            'longitude' => -0.09,  // Domyślna długość geograficzna
        ];

        // Zapisanie fallbackowych koordynatów do odpowiedzi
        $response->getBody()->write(json_encode($coordinates));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
}
}