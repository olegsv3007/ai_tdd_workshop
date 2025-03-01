<?php

namespace App\DDD\User\Domain\Command\Handler;

use App\DDD\User\Domain\Command\UserRegistrationCommand;
use App\DDD\User\Domain\Entity\User;
use App\DDD\User\Domain\Factory\UserFactory;
use App\DDD\User\Domain\Repository\UserRepositoryInterface;
use App\DDD\User\Domain\Service\AuthServiceInterface;

class UserRegistrationHandler
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
        private readonly UserFactory $userFactory,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(UserRegistrationCommand $command): User
    {
        $user = $this->userFactory->createFromRegistrationCommand($command);
        $this->userRepository->store($user);

        $this->authService->createUserToken($user);

        return $user;
    }
}
