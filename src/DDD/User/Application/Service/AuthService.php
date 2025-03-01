<?php

namespace App\DDD\User\Application\Service;

use App\DDD\Shared\Domain\Service\UserPasswordHasherInterface;
use App\DDD\User\Domain\Entity\User;
use App\DDD\User\Domain\Factory\TokenFactory;
use App\DDD\User\Domain\Repository\UserRepositoryInterface;
use App\DDD\User\Domain\Service\AuthServiceInterface;
use App\DDD\User\Domain\Service\AuthTokenGeneratorInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthTokenGeneratorInterface $authTokenGenerator,
        private readonly TokenFactory $tokenFactory,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function createUserToken(User $user): string
    {
        $newToken = $this->authTokenGenerator->generate();

        $token = $this->tokenFactory->createFromAttributes([
            'user' => $user,
            'token' => $newToken,
        ]);

        $user->addToken($token);
        $this->userRepository->update($user);

        return $newToken;
    }

    public function verifyUserCredentials(User $user, string $password): bool
    {
        return $this->userPasswordHasher->isPasswordValid($user, $password);
    }
}
