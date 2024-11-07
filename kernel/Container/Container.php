<?php

namespace App\Container;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;
use App\Http\Redirect;
use App\Http\RedirectInterface;
use App\Http\Request;
use App\Router\Router;
use App\View\View;



class Container
{
    public readonly Request $request;
    public readonly Router $router;
    public readonly RedirectInterface $redirect;
    public readonly View $view;
    public readonly ConfigInterface $config;
    public readonly DatabaseInterface $database;

    public function __construct()
    {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->view = new View();

        $this->redirect = new Redirect();

        $this->request = Request::createFromGlobals();

        $this->config =  new Config();

        $this->database = new Database($this->config);

        $this->router = new Router(
            $this->view,
            $this->request,
            $this->redirect,
            $this->database
        );
    }
}