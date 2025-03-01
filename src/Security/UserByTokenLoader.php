<?php

namespace App\Security;

use App\DDD\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserByTokenLoader
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(string $token): ?UserInterface
    {
        return $this->userRepository->findByToken($token);
    }
}
