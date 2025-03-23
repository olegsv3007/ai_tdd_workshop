<?php

namespace App\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Registry for entity factories
 */
class FactoryRegistry
{
    private ContainerInterface $container;
    private EntityManagerInterface $entityManager;
    
    /** @var array<string, AbstractFactory> */
    private array $factories = [];
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
    }
    
    /**
     * Get a factory for the given entity class
     * 
     * @template T
     * @param class-string<T> $entityClass
     * @return FactoryInterface<T>
     * @throws \InvalidArgumentException If no factory is registered for the entity class
     */
    public function getFactory(string $entityClass): FactoryInterface
    {
        $factoryClass = $this->getFactoryClass($entityClass);
        
        if (!isset($this->factories[$factoryClass])) {
            $this->factories[$factoryClass] = new $factoryClass($this->entityManager, $this->container);
        }
        
        return $this->factories[$factoryClass];
    }
    
    /**
     * Get the factory class for the given entity class
     * 
     * @param string $entityClass
     * @return string
     * @throws \InvalidArgumentException If no factory is registered for the entity class
     */
    private function getFactoryClass(string $entityClass): string
    {
        $entityName = $this->getEntityName($entityClass);
        $factoryClass = "App\\Tests\\Factory\\{$entityName}Factory";
        
        if (!class_exists($factoryClass)) {
            throw new \InvalidArgumentException(
                "No factory found for entity class {$entityClass}. Expected {$factoryClass} to exist."
            );
        }
        
        return $factoryClass;
    }
    
    /**
     * Get the entity name from the entity class
     * 
     * @param string $entityClass
     * @return string
     */
    private function getEntityName(string $entityClass): string
    {
        $parts = explode('\\', $entityClass);
        return end($parts);
    }
}
