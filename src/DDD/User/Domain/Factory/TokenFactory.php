<?php

namespace App\DDD\User\Domain\Factory;

use App\DDD\Shared\Domain\Service\UuidGeneratorInterface;
use App\DDD\User\Domain\Entity\Token;

class TokenFactory
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    /**
     * @param array<string, mixed>  $attributes
     */
    public function createFromAttributes(array $attributes): Token
    {
        return new Token(
            $this->uuidGenerator->generate(),
            $attributes['user'],
            $attributes['token'],
        );
    }
}
