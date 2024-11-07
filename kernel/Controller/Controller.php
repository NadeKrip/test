<?php

namespace App\Controller;

use App\Database\DatabaseInterface;
use App\Http\RedirectInterface;
use App\Http\Request;
use App\View\View;

abstract class Controller
{
    public View $view;
    public Request $request;
    public RedirectInterface $redirect;
    public DatabaseInterface $database;

    public function setDatabase(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function setRedirect(RedirectInterface $redirect)
    {
        $this->redirect = $redirect;
    }

    public function view(string $pageName, string $viewDirectory=''){
        $this->view->page($pageName, $viewDirectory);
    }

    public function setView(View $view){
        $this->view = $view;
    }

    public function setRequest(Request $request){
        $this->request = $request;
    }

    public function extract(array $extractedData)
    {
        $this->view->addExtractList($extractedData);
    }

    public function getId()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $explode = explode('/', trim($uri, '/'));

        return $explode[count($explode) - 1];
    }
}