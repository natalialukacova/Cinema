<?php

namespace App\Interfaces;

interface NewsRepositoryInterface
{
    public function findAll(): array;

    public function findPublished(): array;

    public function findByID(int $newsID): ?array;

    public function create(array $data): int;

    public function update(int $newsID, array $data): void;

    public function delete(int $newsID): bool;
}
