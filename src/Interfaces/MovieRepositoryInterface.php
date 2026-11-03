<?php

namespace App\Interfaces;

use App\Models\Movie;

interface MovieRepositoryInterface
{
    public function findAll(): array;

    public function findByID(int $movieID): ?Movie;

    public function create(Movie $movie): int;

    public function update(Movie $movie): void;

    public function delete(int $movieID): bool;
}