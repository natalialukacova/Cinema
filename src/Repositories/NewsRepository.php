<?php

namespace App\Repositories;

use App\Interfaces\NewsRepositoryInterface;
use PDO;
use RuntimeException;

class NewsRepository implements NewsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function findAll(): array
    {
        $statement = $this->connection->query(
            "SELECT n.*, c.name AS companyName
             FROM News n
             JOIN Company c ON n.companyID = c.companyID
             ORDER BY n.newsID DESC"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findPublished(): array
    {
        $statement = $this->connection->query(
            "SELECT newsID, title, content,
                    imageURL, publishedAt
             FROM News
             WHERE isPublished = 1
               AND publishedAt <= NOW()
             ORDER BY publishedAt DESC, newsID DESC"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByID(int $newsID): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT *
             FROM News
             WHERE newsID = :newsID"
        );

        $statement->execute([
            "newsID" => $newsID
        ]);

        $news = $statement->fetch(PDO::FETCH_ASSOC);

        return $news ?: null;
    }

    private function getCompanyID(): int
    {
        // This project currently manages one cinema.
        $statement = $this->connection->query(
            "SELECT companyID
             FROM Company
             ORDER BY companyID
             LIMIT 1"
        );

        $companyID = $statement->fetchColumn();

        if ($companyID === false) {
            throw new RuntimeException(
                "No cinema company exists."
            );
        }

        return (int) $companyID;
    }

    public function create(array $data): int
    {
        $statement = $this->connection->prepare(
            "INSERT INTO News (
                companyID, title, content,
                imageURL, isPublished, publishedAt
            ) VALUES (
                :companyID, :title, :content,
                :imageURL, :isPublished,
                CASE
                    WHEN :publishNow = 1 THEN NOW()
                    ELSE NULL
                END
            )"
        );

        $statement->execute([
            "companyID" => $this->getCompanyID(),
            "title" => $data["title"],
            "content" => $data["content"],
            "imageURL" => $data["imageURL"],
            "isPublished" => $data["isPublished"],
            "publishNow" => $data["isPublished"]
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $newsID, array $data): void
    {
        $statement = $this->connection->prepare(
            "UPDATE News
             SET title = :title,
                 content = :content,
                 imageURL = :imageURL,
                 isPublished = :isPublished,
                 publishedAt = CASE
                     WHEN :publishNow = 1
                     THEN COALESCE(publishedAt, NOW())
                     ELSE publishedAt
                 END
             WHERE newsID = :newsID"
        );

        $statement->execute([
            "newsID" => $newsID,
            "title" => $data["title"],
            "content" => $data["content"],
            "imageURL" => $data["imageURL"],
            "isPublished" => $data["isPublished"],
            "publishNow" => $data["isPublished"]
        ]);
    }

    public function delete(int $newsID): bool
    {
        $statement = $this->connection->prepare(
            "DELETE FROM News
             WHERE newsID = :newsID"
        );

        $statement->execute([
            "newsID" => $newsID
        ]);

        return $statement->rowCount() > 0;
    }
}
