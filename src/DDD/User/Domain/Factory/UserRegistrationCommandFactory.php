<?php

namespace App\DDD\User\Domain\Factory;

use App\DDD\Shared\Domain\Service\UuidGeneratorInterface;
use App\DDD\User\Domain\Command\UserRegistrationCommand;

class UserRegistrationCommandFactory
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    /**
     * @param array<mixed> $gmailUser
     */
    public function createFromGmailUser(array $gmailUser): UserRegistrationCommand
    {
        $email = $gmailUser['email'];
        $password = $this->uuidGenerator->generate();

        return new UserRegistrationCommand($email, $password);
    }
}
