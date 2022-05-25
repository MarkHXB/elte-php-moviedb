<?php
class Router
{
    private $routes = [];

    public function start()
    {
        $path = $_GET["page"] ?? "index";
        $http_method = $_SERVER["REQUEST_METHOD"];

        foreach ($this->routes as $route) {
            if ($route["path"] === $path && $route["http-method"] === $http_method) {
                $controller_name = $route["controller"];
                $method_name = $route["method"];

                $controller = new $controller_name();
                $controller->$method_name();
                return;
            }
        }
    }

    public function get($path, $controller, $method)
    {
        $this->routes[] = [
            "path" => $path,
            "http-method" => "GET",
            "controller" => $controller,
            "method" => $method
        ];
    }

    public function post($path, $controller, $method)
    { /*...*/
    }
    public function put($path, $controller, $method)
    { /*...*/
    }
    public function delete($path, $controller, $method)
    { /*...*/
    }
}
