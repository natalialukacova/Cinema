<?php

namespace App\Controllers\Website;

use App\Core\View;
use App\Services\MovieService;
use App\Services\NewsService;
use App\Services\ProgramService;
use App\Services\CompanyService;
use App\Services\OpeningHourService;

class HomeController
{
    public function __construct(
        private MovieService $movieService,
        private NewsService $newsService,
        private ProgramService $programService,
        private CompanyService $companyService,
        private OpeningHourService $openingHourService
    ) {
    }

    public function index(): void
    {
        $movies = $this->movieService->getAllMovies();

        // Get the 3 latest published news articles
        $news = array_slice(
            $this->newsService->getPublishedNews(),
            0,
            3
        );

        $todayShowings = $this->programService
            ->getTodayShowings();

        $company = $this->companyService->getCompany();

        $openingHours = $this->openingHourService->getHours();

        View::render(
            "pages/website/home",
            [
                "title" => "Cinema",
                "movies" => $movies,
                "news" => $news,
                "todayShowings" => $todayShowings,
                "company" => $company,
                "openingHours" => $openingHours
            ]
        );
    }
}