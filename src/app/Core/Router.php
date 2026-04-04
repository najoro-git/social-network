<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Cherche une route exacte d'abord
        if (isset($this->routes[$method][$uri])) {
            [$controller, $action] = $this->routes[$method][$uri];
            $ctrl = new $controller();
            $ctrl->$action();
            return;
        }

        // Cherche une route dynamique (ex: /posts/{id})
        foreach ($this->routes[$method] as $path => $handler) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $path);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                // Extrait les noms des paramètres
                preg_match_all('/\{([^}]+)\}/', $path, $paramNames);
                foreach ($paramNames[1] as $i => $name) {
                    $_GET[$name] = $matches[$i];
                }

                [$controller, $action] = $handler;
                $ctrl = new $controller();
                $ctrl->$action();
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page not found";
    }
}