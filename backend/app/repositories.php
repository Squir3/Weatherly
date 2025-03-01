<?php
// backend/app/repositories.php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\RedisUserRepository;
use DI\ContainerBuilder;
use Predis\Client as RedisClient;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        RedisClient::class => function() {
            return new RedisClient([
                'scheme' => 'tcp',
                'host'   => 'redis', // Nazwa usługi Redis w docker-compose.yml
                'port'   => 6379,
            ]);
        },
    ]);
};