<?php

namespace App\Services;

use App\Interfaces\NewsRepositoryInterface;
use DomainException;
use InvalidArgumentException;

class NewsService
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository
    ) {
    }

    public function getAllNews(): array
    {
        return $this->newsRepository->findAll();
    }

    public function getPublishedNews(): array
    {
        return $this->newsRepository->findPublished();
    }

    public function getNewsByID(int $newsID): ?array
    {
        return $this->newsRepository->findByID($newsID);
    }

    public function createNews(array $data): int
    {
        return $this->newsRepository->create(
            $this->validateNews($data)
        );
    }

    public function updateNews(int $newsID, array $data): void
    {
        if ($this->getNewsByID($newsID) === null) {
            throw new DomainException("News article not found.");
        }

        $this->newsRepository->update(
            $newsID,
            $this->validateNews($data)
        );
    }

    public function deleteNews(int $newsID): bool
    {
        return $this->newsRepository->delete($newsID);
    }

    private function validateNews(array $data): array
    {
        foreach (["title", "content", "imageURL", "isPublished"] as $field) {
            if (
                !isset($data[$field]) ||
                !is_string($data[$field])
            ) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }
        }

        $title = trim($data["title"]);
        $content = trim($data["content"]);
        $imageURL = trim($data["imageURL"]);
        $isPublished = $data["isPublished"];

        if ($title === "" || strlen($title) > 200) {
            throw new InvalidArgumentException(
                "Title must contain 1-200 bytes."
            );
        }

        if ($content === "" || strlen($content) > 60000) {
            throw new InvalidArgumentException(
                "Content must contain 1-60000 bytes."
            );
        }

        if ($imageURL !== "") {
            if (
                strlen($imageURL) > 500 ||
                !filter_var($imageURL, FILTER_VALIDATE_URL) ||
                !preg_match("~^https?://~i", $imageURL)
            ) {
                throw new InvalidArgumentException(
                    "Enter a valid HTTP or HTTPS image URL."
                );
            }
        }

        if (!in_array($isPublished, ["0", "1"], true)) {
            throw new InvalidArgumentException(
                "Invalid publication status."
            );
        }

        return [
            "title" => $title,
            "content" => $content,
            "imageURL" => $imageURL !== "" ? $imageURL : null,
            "isPublished" => (int) $isPublished
        ];
    }
}
