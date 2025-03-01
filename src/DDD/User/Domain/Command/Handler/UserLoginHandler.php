<?php

namespace App\DDD\User\Domain\Command\Handler;

use App\DDD\User\Domain\Command\UserLoginCommand;
use App\DDD\User\Domain\Entity\User;
use App\DDD\User\Domain\Repository\UserRepositoryInterface;
use App\DDD\User\Domain\Service\AuthServiceInterface;

class UserLoginHandler
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(UserLoginCommand $command): ?User
    {
        $user = $this->userRepository->findByEmail($command->email);

        if (!$user) {
            return null;
        }

        return $this->authService->verifyUserCredentials($user, $command->password) ? $user : null;
    }
}
