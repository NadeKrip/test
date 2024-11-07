<?php

namespace App\src\Contoller\Common;

use App\Controller\Controller;

class HomeController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $this->extract([
            'controller' => $this
        ]);


        $this->view("Common/index");
    }
}