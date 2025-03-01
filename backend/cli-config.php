<?php

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

// Wczytaj zmienne środowiskowe
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

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

// Ładowanie konfiguracji migracji
$config = new PhpFile(__DIR__ . '/migrations-db.php');

// Tworzenie i zwrócenie DependencyFactory
return DependencyFactory::fromConnection(
    $config,
    new ExistingConnection($connection)
);