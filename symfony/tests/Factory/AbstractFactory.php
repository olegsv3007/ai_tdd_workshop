<?php

namespace App\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract factory for entities
 * 
 * @template T
 * @implements FactoryInterface<T>
 */
abstract class AbstractFactory implements FactoryInterface
{
    protected Generator $faker;
    protected EntityManagerInterface $entityManager;
    protected ContainerInterface $container;
    
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->faker = Factory::create();
    }
    
    /**
     * Create an entity with the given attributes and persist it
     * 
     * @param array<string, mixed> $attributes
     * @return T
     */
    public function create(array $attributes = []): object
    {
        $entity = $this->make($attributes);
        
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        return $entity;
    }
    
    /**
     * Make an entity with the given attributes but don't persist it
     * 
     * @param array<string, mixed> $attributes
     * @return T
     */
    public function make(array $attributes = []): object
    {
        $entity = $this->instantiate($this->getDefaults($attributes));
        
        return $entity;
    }
    
    /**
     * Get the default attributes for the entity
     * 
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    abstract protected function getDefaults(array $attributes = []): array;
    
    /**
     * Instantiate the entity with the given attributes
     * 
     * @param array<string, mixed> $attributes
     * @return T
     */
    abstract protected function instantiate(array $attributes): object;
}
