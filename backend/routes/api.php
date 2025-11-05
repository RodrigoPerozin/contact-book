<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactsController;

Route::prefix('contact')->group(function () {
    Route::get('/', [ContactsController::class, 'find']); // GET /api/contact
    Route::post('/', [ContactsController::class, 'create']); // POST /api/contact
    Route::put('/{id}', [ContactsController::class, 'update']); // PUT /api/contact/{id}
    Route::delete('/{id}', [ContactsController::class, 'delete']); // DELETE /api/contact/{id}
    Route::get('/default-picture', [ContactsController::class, 'defaultPicture']); // GET /api/contact/default-picture
});

//Se não houver endpoint correspondente, retorna um erro 404 em formato JSON
Route::fallback(function (Request $request) {
    return response()->json([
        'error' => 'Endpoint not found',
        'path' => $request->path(),
        'method' => $request->method(),
    ], 404);
});