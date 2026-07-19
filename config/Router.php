<?php
/**
 * Application Router
 */

class Router {
    private $routes = [];
    private $currentRoute = null;

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function put($path, $callback) {
        $this->routes['PUT'][$path] = $callback;
    }

    public function delete($path, $callback) {
        $this->routes['DELETE'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path from URI - handle both XAMPP and development server paths
        $basePaths = ['/CityOfCalambaDev', '/CityOfCalambaDev/public'];
        foreach ($basePaths as $basePath) {
            if (strpos($path, $basePath) === 0) {
                $path = substr($path, strlen($basePath));
                break;
            }
        }
        
        $path = empty($path) || $path === '/' ? '/' : rtrim($path, '/');

        // Check for exact match
        if (isset($this->routes[$method][$path])) {
            $this->currentRoute = $this->routes[$method][$path];
            return $this->executeRoute();
        }

        // Check for parameterized routes
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            if ($this->matchRoute($route, $path, $params)) {
                $this->currentRoute = $callback;
                return $this->executeRoute($params);
            }
        }

        // 404 Not Found
        http_response_code(404);
        return $this->render('errors/404');
    }

    private function matchRoute($route, $path, &$params) {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $path, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (!is_numeric($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }

    private function executeRoute($params = []) {
        if (is_callable($this->currentRoute)) {
            return call_user_func_array($this->currentRoute, [$params]);
        } elseif (is_string($this->currentRoute)) {
            list($controller, $method) = explode('@', $this->currentRoute);
            $controllerClass = 'App\\Controllers\\' . $controller;
            
            if (class_exists($controllerClass)) {
                $instance = new $controllerClass();
                return call_user_func_array([$instance, $method], [$params]);
            }
        }

        return null;
    }

    public function render($view, $data = []) {
        extract($data);
        $viewPath = base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php');
        
        if (file_exists($viewPath)) {
            ob_start();
            require $viewPath;
            return ob_get_clean();
        }

        return 'View not found: ' . $viewPath;
    }
}
