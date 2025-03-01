<?php

namespace App\DataFixtures\User;

use App\DataFixtures\BaseFixture;
use App\Tests\Factory\User\UserFactory;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixture
{
    public const REFERENCE = 'user';
    public const USER_EMAIL = 'email@example.com';
    public const PASSWORD = '12345678';

    private UserFactory $userFactory;

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
        $this->userFactory = new UserFactory();
    }

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        $user = $this->userFactory->create([
            'id' => Uuid::uuid4(),
            'email' => self::USER_EMAIL,
            'token' => Uuid::uuid4()->toString(),
        ]);

        $hashedPassword = $this->passwordHasher->hashPassword($user, self::PASSWORD);

        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }
}
