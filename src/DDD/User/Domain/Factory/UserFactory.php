<?php

namespace App\DDD\User\Domain\Factory;

use App\DDD\Shared\Domain\Service\UserPasswordHasherInterface;
use App\DDD\Shared\Domain\Service\UuidGeneratorInterface;
use App\DDD\User\Domain\Command\UserRegistrationCommand;
use App\DDD\User\Domain\Entity\User;

class UserFactory
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function createFromRegistrationCommand(UserRegistrationCommand $command): User
    {
        $user = new User($this->uuidGenerator->generate(), $command->email);

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $command->password);
        $user->setPassword($hashedPassword);

        return $user;
    }
}
