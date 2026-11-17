<?php

namespace App\Models;

class News
{
    public function __construct(
        private int $newsID,
        private int $companyID,
        private string $title,
        private string $content,
        private ?string $publishedAt,
        private ?string $imageURL,
        private bool $isPublished,
        private ?string $companyName = null
    ) {
    }

    public function getNewsID(): int
    {
        return $this->newsID;
    }

    public function getCompanyID(): int
    {
        return $this->companyID;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPublishedAt(): ?string
    {
        return $this->publishedAt;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
}
