<?php
namespace Router;

class Router
{
    private array $routes = [];

    public function addRoute($method, $url, $controller, $action): void {
        // Converti il pattern {param} in regex
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<\1>[^/]+)', $url);
        $pattern = '#^' . $pattern . '$#'; // Aggiunge delimitatori e inizio/fine stringa
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
            'original' => $url // utile per debugging
        ];
    }

    public function match($url, $method): array {
        if (!isset($this->routes[$method])) {
            return [];
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $url, $matches)) {
                // Solo parametri con nome
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => $params
                ];
            }
        }

        return []; // Nessuna corrispondenza
    }
}
