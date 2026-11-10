<?php

namespace App\Controllers\Website;

use App\Core\View;
use App\Services\NewsService;

class NewsController
{
    public function __construct(
        private NewsService $newsService
    ) {
    }

    public function index(): void
    {
        $news = $this->newsService->getPublishedNews();

        View::render(
            "pages/website/news/news-list",
            [
                "title" => "Cinema News",
                "news" => $news
            ]
        );
    }
}
