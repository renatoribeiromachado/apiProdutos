<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('auth', 'Auth\AuthApiController@authenticate');

Route::group(['middleware' => ['jwt.auth']] , function(){
    Route::apiResource('produtos', 'api\ProdutoController', ['except' =>['create','edit']]);
});

