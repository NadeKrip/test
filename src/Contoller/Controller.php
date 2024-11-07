<?php

namespace App\User\Contoller;

abstract class Controller extends \App\Controller\Controller
{
    private string $directory = "/user/View/";
    public function view(string $pageName, string $viewDirectory = ''){
        $this->view->page($pageName, $this->directory);
    }



}