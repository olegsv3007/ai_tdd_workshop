<?php

namespace App\DDD\User\Domain\Entity;

class Token
{
    private string $id;
    private User $user;
    private string $token;

    public function __construct(
        string $id,
        User $user,
        string $token,
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->token = $token;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getValue(): string
    {
        return $this->token;
    }
}
