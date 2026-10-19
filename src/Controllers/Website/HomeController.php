<?php

namespace App\Controllers\Website;

class HomeController
{
    public function index(): void
    {
        require __DIR__
            . "/../../../views/pages/Website/home.php";
    }
}