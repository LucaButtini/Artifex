<?php
namespace Router;

class Router
{
    private array $routes = [];

    public function addRoute($method, $url, $controller, $action): void {
        // Converti le variabili tra {} in regex
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<\1>[^/]+)', $url);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function match($url, $method): array {
        if (!isset($this->routes[$method])) {
            return [];
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $url, $matches)) {
                // Filtra solo i parametri con nome (senza indici numerici)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => $params
                ];
            }
        }

        return [];
    }
}

