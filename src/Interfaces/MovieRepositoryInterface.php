<?php

namespace App\Interfaces;

use App\Models\Movie;

interface MovieRepositoryInterface
{
    /**
     * @return Movie[]
     */
    public function findAll(): array;

    public function findByID(int $movieID): ?Movie;
}