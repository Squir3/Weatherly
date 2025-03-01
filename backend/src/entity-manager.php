<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

// Ładowanie zmiennych środowiskowych
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Parametry połączenia z bazą danych
$connectionParams = [
    'driver'   => $_ENV['DB_DRIVER'],
    'host'     => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'dbname'   => $_ENV['DB_NAME'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset'  => 'utf8', // Ustawienie zestawu znaków
];

// Konfiguracja DBAL
$config = new Configuration();

// Tworzenie połączenia
$connection = DriverManager::getConnection($connectionParams, $config);

return $connection;