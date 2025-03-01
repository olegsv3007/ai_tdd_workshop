<?php

namespace App\DDD\User\Domain\Entity;

use App\DDD\User\Domain\Collection\TokenCollection;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private ?string $id;
    private string $email;
    private ?string $password;
    private Collection $tokens;
    private DateTimeInterface $createdAt;

    public function __construct(
        string $id,
        string $email,
    ) {
        $this->id = $id;
        $this->email = $email;

        $this->tokens = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getTokens(): TokenCollection
    {
        return new TokenCollection($this->tokens);
    }

    public function addToken(Token $token): self
    {
        if ($this->tokens->contains($token)) {
            return $this;
        }

        $this->tokens->add($token);

        return $this;
    }

    public function removeToken(Token $token): self
    {
        $this->tokens->removeElement($token);

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
