<?php

namespace App;

use App\Database\Database;
use App\Router\Router;
use App\Http\Request;
use App\Container\Container;

class App
{
    private $container;

    public function __construct()
    {
        $this->container = new Container;
    }

    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $explode = explode('/', trim($uri, '/'));

        if (count($explode) > 1) {
            $this->container->router->route(
                '/'.$explode[0]."/id",
                $this->container->request->method()
            );
            return;
        }

        $this->container->router->route(
            $this->container->request->uri(),
            $this->container->request->method()
        );

    }
}
