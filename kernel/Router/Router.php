<?php

namespace App\Router;

use App\Database\DatabaseInterface;
use App\Http\RedirectInterface;
use App\View\View;
use App\Http\RequestInterface;
class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function route(string $url, string $method){
        $route = $this->findRoute($url, $method);

        if (! $route) {
            $this->notFound();
        }

        if ($route->hasMiddlewares()) {
            foreach ($route->getMiddlewares() as $middleware) {
                $middleware = new $middleware($this->request, $this->redirect);

                $middleware->handle();
            }
        }

        if (is_array($route->getAction())){

            [$controller, $action] = $route->getAction();

            $controller = new $controller();

            call_user_func([$controller, 'setView'],$this->view);

            call_user_func([$controller, 'setRequest'],$this->request);

            call_user_func([$controller, 'setRedirect'], $this->redirect);

            call_user_func([$controller, 'setDatabase'], $this->database);

            call_user_func([$controller, $action]);
        }
        else{
            call_user_func($route->getAction());
        }
    }

    private function findRoute(string $uri, string $method): Route|false
    {
        if (! isset($this->routes[$method][$uri])) {
            return false;
        }

        return $this->routes[$method][$uri];
    }

    public function __construct(
        private View $view,
        private RequestInterface $request,
        private RedirectInterface $redirect,
        private DatabaseInterface $database,

    ) {
        $this->initRoutes();
    }

    private function initRoutes()
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route) {

            $this->routes[$route->getMethod()][$route->getUri()] = $route;
        }
    }

    private function getRoutes()
    {
        return  require APP_PATH. "/configs/routes.php";
    }

    private function notFound()
    {
        $this->redirect->to('/');
    }
}