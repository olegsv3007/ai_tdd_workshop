<?php

namespace App\Tests\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractFixture
{
    protected EntityManagerInterface $entityManager;
    protected ContainerInterface $container;

    /**
     * Set the entity manager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Set the container
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * Load the fixture and return the created entity
     * 
     * @return object The entity created by the fixture
     */
    abstract public function load(): object;
}
