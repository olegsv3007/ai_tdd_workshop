<?php

namespace App\Tests\Acceptance;

use App\Application\Bus\QueryBusContract;
use App\Tests\Fixture\AbstractFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AcceptanceTestCase extends WebTestCase
{
    protected ?EntityManagerInterface $entityManager = null;
    protected ?ContainerInterface $container = null;
    private static $initialized = false;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize once per test class
        if (!self::$initialized) {
            self::$initialized = true;
            
            // Initialize client and get container
            self::ensureKernelShutdown(); // Make sure kernel is shut down before creating client
            $client = static::createClient();
            $this->container = static::getContainer();
            $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
            
            // Start a transaction for test isolation
            if (!$this->entityManager->getConnection()->isTransactionActive()) {
                $this->entityManager->beginTransaction();
            }
        } else {
            // Reuse existing container and entity manager
            $this->container = static::getContainer();
            $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
        }
    }
    
    /**
     * Load a fixture and return the created entity
     * 
     * @template T
     * @param class-string<AbstractFixture> $fixtureClass The fixture class to load
     * @param class-string<T> $expectedEntityClass The expected entity class to be returned
     * @return T The entity created by the fixture
     */
    protected function loadFixture(string $fixtureClass, string $expectedEntityClass): object
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
}