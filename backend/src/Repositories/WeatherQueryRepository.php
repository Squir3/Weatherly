<?php
namespace App\Repositories;

use Doctrine\DBAL\Connection;
use App\Entities\WeatherQuery;

class WeatherQueryRepository
{
    private Connection $connection;
    private string $tableName = 'weather';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(int $id): ?WeatherQuery
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $result = $stmt->executeQuery()->fetchAssociative();

        if (!$result) {
            return null;
        }

        return $this->mapToEntity($result);
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM ' . $this->tableName;
        $stmt = $this->connection->executeQuery($sql);
        $results = $stmt->fetchAllAssociative();

        $weatherQueries = [];
        foreach ($results as $result) {
            $weatherQueries[] = $this->mapToEntity($result);
        }

        return $weatherQueries;
    }

    public function save(WeatherQuery $weatherQuery): void
    {
        $this->connection->insert($this->tableName, [
            'query_date' => $weatherQuery->getQueryDate()->format('Y-m-d H:i:s'),
            'location' => $weatherQuery->getLocation(),
            'latitude' => $weatherQuery->getLatitude(),
            'longitude' => $weatherQuery->getLongitude(),
            'temperature' => $weatherQuery->getTemperature(),
            'humidity' => $weatherQuery->getHumidity(),
            'weather_description' => $weatherQuery->getWeatherDescription(),
        ]);
    }

    public function update(WeatherQuery $weatherQuery): void
    {
        $this->connection->update($this->tableName, [
            'query_date' => $weatherQuery->getQueryDate()->format('Y-m-d H:i:s'),
            'location' => $weatherQuery->getLocation(),
            'latitude' => $weatherQuery->getLatitude(),
            'longitude' => $weatherQuery->getLongitude(),
            'temperature' => $weatherQuery->getTemperature(),
            'humidity' => $weatherQuery->getHumidity(),
            'weather_description' => $weatherQuery->getWeatherDescription(),
        ], ['id' => $weatherQuery->getId()]);
    }

    public function delete(int $id): void
    {
        $this->connection->delete($this->tableName, ['id' => $id]);
    }

    private function mapToEntity(array $result): WeatherQuery
    {
        $weatherQuery = new WeatherQuery();
        $weatherQuery->setId((int)$result['id']);
        $weatherQuery->setQueryDate(new \DateTime($result['query_date']));
        $weatherQuery->setLocation($result['location']);
        $weatherQuery->setLatitude((float)$result['latitude']);
        $weatherQuery->setLongitude((float)$result['longitude']);
        $weatherQuery->setTemperature((float)$result['temperature']);
        $weatherQuery->setHumidity((float)$result['humidity']);
        $weatherQuery->setWeatherDescription($result['weather_description']);

        return $weatherQuery;
    }
}