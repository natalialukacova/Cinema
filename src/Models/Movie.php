<?php

namespace App\Models;

class Movie
{
    private ?int $movieID;
    private string $title;
    private string $releaseDate;
    private int $durationMinutes;
    private ?string $description;
    private string $ageRating;
    private ?string $imageURL;

    public function __construct(
        ?int $movieID,
        string $title,
        string $releaseDate,
        int $durationMinutes,
        ?string $description,
        string $ageRating,
        ?string $imageURL
    ) {
        $this->movieID = $movieID;
        $this->title = $title;
        $this->releaseDate = $releaseDate;
        $this->durationMinutes = $durationMinutes;
        $this->description = $description;
        $this->ageRating = $ageRating;
        $this->imageURL = $imageURL;
    }

    public function getMovieID(): ?int
    {
        return $this->movieID;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getDurationMinutes(): int
    {
        return $this->durationMinutes;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAgeRating(): string
    {
        return $this->ageRating;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }
}