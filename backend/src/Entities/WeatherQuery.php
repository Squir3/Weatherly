<?php
namespace App\Entities;

class WeatherQuery
{
    private ?int $id = null;
    private \DateTime $queryDate;
    private string $location;
    private float $latitude;
    private float $longitude;
    private ?float $temperature = null;
    private ?float $humidity = null;
    private ?string $weatherDescription = null;

    public function __construct()
    {
        $this->queryDate = new \DateTime();
    }

    // Gettery i settery
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getQueryDate(): \DateTime
    {
        return $this->queryDate;
    }

    public function setQueryDate(\DateTime $queryDate): void
    {
        $this->queryDate = $queryDate;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): void
    {
        $this->temperature = $temperature;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(?float $humidity): void
    {
        $this->humidity = $humidity;
    }

    public function getWeatherDescription(): ?string
    {
        return $this->weatherDescription;
    }

    public function setWeatherDescription(?string $weatherDescription): void
    {
        $this->weatherDescription = $weatherDescription;
    }
}