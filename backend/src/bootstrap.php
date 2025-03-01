<?php

use DI\Container;
use App\Controllers\WeatherController;
use Slim\Factory\AppFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Dotenv\Dotenv;
use Predis\Client as RedisClient;

// 1. Autoload dependencies
require __DIR__ . '/../vendor/autoload.php';

// 2. Env detection
$appEnv = $_ENV['APP_ENV'] ?? 'development';

// 3. Path to files .env
$envFile = $appEnv === 'production' ? '.env.production' : '.env';

// 4. Loading env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..', $envFile);
$dotenv->safeLoad();

// 5. Create Container using PHP-DI
$container = new Container();

// 6. Database connection parameters
$connectionParams = [
    'driver'   => $_ENV['DB_DRIVER'],
    'host'     => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'dbname'   => $_ENV['DB_NAME'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset'  => 'utf8',
];

// 7. Register Doctrine\DBAL\Connection in the container
$container->set(Connection::class, function() use ($connectionParams) {
    return DriverManager::getConnection($connectionParams);
});

// Register RedisClient in the container
$container->set(RedisClient::class, function() {
    return new RedisClient([
        'scheme' => 'tcp',
        'host'   => 'redis', // Service name from docker-compose.yml
        'port'   => 6379,
    ]);
});

// 8. Register Controllers in the container
$container->set(WeatherController::class, function($container) {
    return new WeatherController(
        $container->get(Connection::class),
        $container->get(RedisClient::class)
    );
});

$container->set(App\Controllers\UserLocationController::class, function($container) {
    return new App\Controllers\UserLocationController();
});

// 9. Set the container in AppFactory
AppFactory::setContainer($container);

// 10. Create Slim app
$app = AppFactory::create();

// 11. Add CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// 12. Load routes from routes.php
(require __DIR__ . '/routes.php')($app);

// 13. Run the app
$app->run();
return $app;
