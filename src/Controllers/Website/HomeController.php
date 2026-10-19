<?php

namespace App\Controllers\Website;

use App\Core\View;

class HomeController
{
    public function index(): void
    {
        View::render(
            "pages/Website/home",
            [
                "title" => "Cinema"
            ]
        );
    }
}