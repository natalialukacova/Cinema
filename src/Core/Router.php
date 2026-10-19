<?php

namespace App\Core;

class Router
{
    private string $requestPath;
    private string $requestMethod;
    private bool $routeFound = false;

    public function __construct(
        string $requestUri,
        string $requestMethod,
        string $basePath = ""
    ) {
        $path = parse_url($requestUri, PHP_URL_PATH) ?? "/";

        if (
            $basePath !== ""
            && str_starts_with($path, $basePath)
        ) {
            $path = substr($path, strlen($basePath));
        }

        $this->requestPath = $this->normalizePath($path);
        $this->requestMethod = strtoupper($requestMethod);
    }

    public function get(string $route, callable $action): void
    {
        $this->handleRoute("GET", $route, $action);
    }

    public function post(string $route, callable $action): void
    {
        $this->handleRoute("POST", $route, $action);
    }

    private function handleRoute(
        string $method,
        string $route,
        callable $action
    ): void {
        if ($this->routeFound) {
            return;
        }

        $route = $this->normalizePath($route);

        if (
            $this->requestMethod === $method
            && $this->requestPath === $route
        ) {
            $this->routeFound = true;
            $action();
        }
    }

    public function notFound(callable $action): void
    {
        if (!$this->routeFound) {
            http_response_code(404);
            $action();
        }
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, "/");

        return $path === "" ? "/" : "/" . $path;
    }
}