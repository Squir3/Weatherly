<?php
// backend/src/Infrastructure/Persistence/User/RedisUserRepository.php

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Predis\Client;

class RedisUserRepository implements UserRepository
{
    private Client $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function find(int $id): ?User
    {
        $data = $this->redis->get("user:{$id}");
        if ($data) {
            $userData = json_decode($data, true);
            return new User($userData['id'], $userData['name'], $userData['email']);
        }
        return null;
    }

    public function save(User $user): void
    {
        $this->redis->set("user:{$user->getId()}", json_encode([
            'id'    => $user->getId(),
            'name'  => $user->getName(),
            'email' => $user->getEmail(),
        ]));
    }

}