<?php

namespace App\Tests\Factory;

/**
 * Interface for entity factories
 * 
 * @template T
 */
interface FactoryInterface
{
    /**
     * Create an entity with the given attributes
     * 
     * @param array<string, mixed> $attributes
     * @return T
     */
    public function create(array $attributes = []): object;
    
    /**
     * Make an entity with the given attributes but don't persist it
     * 
     * @param array<string, mixed> $attributes
     * @return T
     */
    public function make(array $attributes = []): object;
}
