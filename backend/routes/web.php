<?php

use App\Http\Controllers\ContactsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ContactsController::class)->group(function () {
    Route::get('/api/contact', 'find'); // Get all contacts with optional filters and pagination
    Route::delete('/api/contact/{id}', 'delete'); // Delete a contact by ID
    Route::post('/api/contact', 'create'); // Create a new contact
    Route::put('/api/contact/{id}', 'update'); // Update a contact by ID
    Route::get('/api/contact/default-picture', 'defaultPicture'); // Get default contact picture
});
