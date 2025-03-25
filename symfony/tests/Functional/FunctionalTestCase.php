<?php

namespace App\Tests\Functional;

use App\Application\Bus\CommandBusContract;
use App\Application\Bus\QueryBusContract;
use App\Tests\Fixture\AbstractFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class FunctionalTestCase extends KernelTestCase
{
    protected ?EntityManagerInterface $entityManager = null;
    protected ?ContainerInterface $container = null;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        
        $this->container = static::getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
        
        // Start a transaction for test isolation
        $this->entityManager->beginTransaction();
    }
    
    /**
     * Load a fixture and return the created entity
     * 
     * @template T
     * @param class-string<AbstractFixture> $fixtureClass The fixture class to load
     * @param class-string<T> $expectedEntityClass The expected entity class to be returned
     * @return T The entity created by the fixture
     */
    protected function loadFixture(string $fixtureClass, string $expectedEntityClass)
    {
        /** @var AbstractFixture $fixture */
        $fixture = new $fixtureClass();
        $fixture->setEntityManager($this->entityManager);
        $fixture->setContainer($this->container);
        
        $entity = $fixture->load();
        
        if (!$entity instanceof $expectedEntityClass) {
            throw new \InvalidArgumentException(
                sprintf('Expected entity of class %s, got %s', $expectedEntityClass, get_class($entity))
            );
        }
        
        return $entity;
    }
    
    protected function tearDown(): void
    {
        // Roll back transaction to clean up after test
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }
        
        parent::tearDown();
        
        $this->entityManager->close();
        $this->entityManager = null;
        $this->container = null;
    }

    protected function getQueryBus(): QueryBusContract
    {
        return $this->container->get(QueryBusContract::class);
    }

    protected function getCommandBus(): CommandBusContract
    {
        return $this->container->get(CommandBusContract::class);
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    protected static function getService(string $className)
    {
        return self::getContainer()->get($className);
    }
}
