<?php

use App\Router\Route;
use App\src\Contoller\Common\HomeController;
use App\src\Contoller\Common\CreateController;
use App\src\Contoller\Common\GetController;
use App\src\Contoller\Common\UpdateContoller;
use App\src\Contoller\Common\DeleteContoller;

return [
    Route::get('/', [HomeController::class, 'index']),

    Route::post('/create', [CreateController::class, 'index']),
    Route::get('/get', [GetController::class, 'index']),
    Route::get('/get/id', [GetController::class, 'user']),

    Route::patch('/update/id', [UpdateContoller::class, 'index']),
    Route::delete('/delete/id', [DeleteContoller::class, 'index']),



];







