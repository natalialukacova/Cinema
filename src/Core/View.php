<?php

namespace App\Core;

class View
{
    public static function render(
        string $view,
        array $data = []
    ): void {
        $viewFile = __DIR__
            . "/../../views/"
            . $view
            . ".php";

        if (!file_exists($viewFile)) {
            throw new \RuntimeException(
                "View not found: " . $view
            );
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require __DIR__
            . "/../../views/layouts/main.php";
    }
}