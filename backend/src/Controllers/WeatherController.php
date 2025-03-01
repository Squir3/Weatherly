<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\DBAL\Connection;
use Predis\Client as RedisClient;
use App\Entities\WeatherQuery;
use App\Repositories\WeatherQueryRepository;
use GuzzleHttp\Client;

class WeatherController
{
    private WeatherQueryRepository $weatherQueryRepository;
    private Client $httpClient;
    private string $apiKey;
    private RedisClient $redis;

    public function __construct(Connection $connection, RedisClient $redis)
    {
        $this->redis = $redis;
        $this->weatherQueryRepository = new WeatherQueryRepository($connection);
        $this->httpClient = new Client();
        $this->apiKey = $_ENV['OPENWEATHERMAP_API_KEY'];
    }

    // Metoda GET /weather
   public function getWeather(Request $request, Response $response, $args): Response
{
    $params = $request->getQueryParams();
    $lat = $params['lat'] ?? null;
    $lon = $params['lon'] ?? null;

    if (!isset($lat, $lon) || !is_numeric($lat) || !is_numeric($lon)) {
        $error = ['error' => 'Parametry "lat" i "lon" są wymagane i muszą być liczbami.'];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Zaokrąglij współrzędne
    $roundedLat = round((float)$lat, 2);
    $roundedLon = round((float)$lon, 2);
    $cacheKey = "weather_{$roundedLat}_{$roundedLon}";
    error_log("Używany klucz cache: " . $cacheKey);

    // Sprawdź cache
    try {
        $cachedData = $this->redis->get($cacheKey);
        if ($cachedData) {
            error_log("Dane pobrano z cache Redis.");
            $weatherData = json_decode($cachedData, true);
        } else {
            error_log("Brak danych w cache. Pobieranie z API.");
            // Pobierz dane z API
            $apiResponse = $this->httpClient->get('https://api.openweathermap.org/data/2.5/weather', [
                'query' => [
                    'lat' => $roundedLat,
                    'lon' => $roundedLon,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang' => 'eng',
                ],
            ]);

            $weatherData = json_decode($apiResponse->getBody(), true);

            // Zapisz dane do cache
            try {
                $this->redis->setex($cacheKey, 600, json_encode($weatherData)); // Cache na 10 minut
                error_log("Dane zapisane w cache Redis.");
            } catch (\Exception $e) {
                error_log("Błąd Redis SETEX: " . $e->getMessage());
            }
        }
    } catch (\Exception $e) {
        error_log("Błąd Redis GET: " . $e->getMessage());
        $weatherData = null;
    }

    if ($weatherData) {
        // Kontynuuj przetwarzanie danych pogodowych
        $weatherQuery = new WeatherQuery();
        $weatherQuery->setQueryDate(new \DateTime());
        $weatherQuery->setLocation($weatherData['name'] ?? 'Nieznana lokalizacja');
        $weatherQuery->setLatitude($roundedLat);
        $weatherQuery->setLongitude($roundedLon);
        $weatherQuery->setTemperature($weatherData['main']['temp'] ?? null);
        $weatherQuery->setHumidity($weatherData['main']['humidity'] ?? null);
        $weatherQuery->setWeatherDescription($weatherData['weather'][0]['description'] ?? null);

        $this->weatherQueryRepository->save($weatherQuery);

        $response->getBody()->write(json_encode($weatherData));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $error = ['error' => 'Nie udało się pobrać danych pogodowych.'];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
 }
}   