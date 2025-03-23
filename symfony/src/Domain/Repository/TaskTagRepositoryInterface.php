<?php

namespace App\Domain\Repository;

use App\Domain\Entity\TaskTag;

interface TaskTagRepositoryInterface
{
    public function save(TaskTag $taskTag): void;
    public function remove(TaskTag $taskTag): void;
    public function findById(int $id): ?TaskTag;
    public function findAll(): array;
    public function findByName(string $name): array;
}
