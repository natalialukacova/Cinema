<?php

namespace App\Interfaces;

use App\Models\News;

interface NewsRepositoryInterface
{
    public function findAll(): array;

    public function findPublished(): array;

    public function findByID(int $newsID): ?News;

    public function create(array $data): int;

    public function update(int $newsID, array $data): void;

    public function delete(int $newsID): bool;
}
