<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

//Just a casual API home page
Route::get('/', function () {
    return view('welcome');
});


//Se não houver endpoint correspondente, retorna um erro 404 em formato JSON
Route::fallback(function (Request $request) {
    return response()->json([
        'error' => 'Endpoint not found',
        'path' => $request->path(),
        'method' => $request->method(),
    ], 404);
});