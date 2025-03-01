<?php

namespace App\DDD\Shared\Domain\Collection;

use Traversable;

abstract class EntityCollection implements \Countable, \IteratorAggregate
{
    private mixed $items = [];

    abstract protected static function getItemType(): string;

    /**
     * @param mixed[] $items
     */
    public function __construct(iterable $items = [])
    {
        self::assertItemsInstanceOf($items, static::getItemType());

        $this->setItems($items);
    }

    /**
     * Rewrite for value object collection
     *
     * @todo remove after adding __equals magic method to php object
     */
    protected static function getEquivalenceComparator(object $comparableItem): callable
    {
        return static fn (object $item) => $item === $comparableItem;
    }

    /**
     * Rewrite for value object collection
     *
     * @todo remove after adding __equals magic method to php object
     */
    protected static function getNotEquivalenceComparator(object $comparableItem): callable
    {
        return static fn (object $item) => $item !== $comparableItem;
    }

    final public function merge(self $collection): self
    {
        $items = $this->getItems();

        foreach ($collection as $item) {
            $items[] = $item;
        }

        return new static($items);
    }

    final public function slice(int $offset, ?int $length = null): self
    {
        return new static(array_slice($this->getItems(), $offset, $length));
    }

    final public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @return mixed[]
     */
    final public function map(callable $mapper): iterable
    {
        return array_map($mapper, $this->getItems());
    }

    final public function has(object $neededObject): bool
    {
        return (bool) $this->find(static::getEquivalenceComparator($neededObject));
    }

    final public function equals(self $collection): bool
    {
        foreach ($collection as $item) {
            if (!$this->has($item)) {
                return false;
            }
        }

        return $this->count() === $collection->count();
    }

    final public function remove(object $objectForRemoving): self
    {
        return $this->filter(static::getNotEquivalenceComparator($objectForRemoving));
    }

    final public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->getItems());
    }

    public function count(): int
    {
        return count($this->getItems());
    }

    final protected function filter(callable $filter): self
    {
        return new static(array_values(array_filter($this->getItems(), $filter)));
    }

    final protected function find(callable $expression): ?object
    {
        foreach ($this->getItems() as $item) {
            if ($expression($item)) {
                return $item;
            }
        }

        return null;
    }

    final protected function getFirstItem(): ?object
    {
        $firstItem = current($this->getItems());

        return $firstItem ?: null;
    }

    final protected function getLastItem(): ?object
    {
        $items = $this->getItems();
        $lastItem = end($items);

        return $lastItem ?: null;
    }

    /**
     * @param mixed[] $items
     */
    private function setItems(iterable $items): void
    {
        $this->items = $items instanceof \Traversable ? iterator_to_array($items) : $items;
    }

    /**
     * @return mixed[]
     */
    private function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param mixed[] $items
     */
    private static function assertItemsInstanceOf(iterable $items, string $expectedItemType): void
    {
        foreach ($items as $item) {
            if (!$item instanceof $expectedItemType) {
                throw new \InvalidArgumentException(sprintf(
                    'Collection should has only items of "%s".',
                    $expectedItemType,
                ));
            }
        }
    }
}
