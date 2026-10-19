<?php

namespace App\Core;

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(
            function (string $className): void {
                $prefix = "App\\";

                if (!str_starts_with($className, $prefix)) {
                    return;
                }

                $relativeClass = substr(
                    $className,
                    strlen($prefix)
                );

                $file = dirname(__DIR__)
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        "\\",
                        DIRECTORY_SEPARATOR,
                        $relativeClass
                    )
                    . ".php";

                if (is_file($file)) {
                    require_once $file;
                }
            }
        );
    }
}