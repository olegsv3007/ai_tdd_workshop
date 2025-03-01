<?php

namespace App\DataFixtures\User;

use App\DataFixtures\BaseFixture;
use App\Tests\Factory\User\TokenFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class TokenFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const REFERENCE = 'token';

    private TokenFactory $tokenFactory;

    public function __construct()
    {
        $this->tokenFactory = new TokenFactory();
    }

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $user = $this->referenceRepository->getReference(UserFixtures::REFERENCE);

        $token = $this->tokenFactory->create([
            'id' => Uuid::uuid4(),
            'user' => $user,
            'token' => Uuid::uuid4()->toString(),
        ]);

        $manager->persist($token);
        $manager->flush();

        $this->addReference(self::REFERENCE, $token);
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
