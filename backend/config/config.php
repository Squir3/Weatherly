<?php
// config/config.php

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

// Ładowanie zmiennych środowiskowych
Dotenv::createImmutable(__DIR__ . '/../')->load();

// Parametry połączenia z bazą danych
$connectionParams = [
    'dbname'   => $_ENV['DB_NAME'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'host'     => $_ENV['DB_HOST'],
    'driver'   => $_ENV['DB_DRIVER'],
    'port'     => $_ENV['DB_PORT'],
    'charset'  => 'utf8',
];

// Tworzenie połączenia
$connection = DriverManager::getConnection($connectionParams);

return $connection;