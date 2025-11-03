<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Contact;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/contact', function () {
    return Contact::all();
});


Route::post('/api/contact', function (Request $request) {
    $data = $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email|unique:contacts,email',
        'phone'          => 'nullable|string|max:20',
        'cep'            => 'nullable|string|max:10',
        'city'           => 'nullable|string|max:100',
        'country'        => 'nullable|string|max:100',
        'neighborhood'   => 'nullable|string|max:100',
        'street_address' => 'nullable|string|max:255',
        'house_number'   => 'nullable|string|max:20',
        'complement'     => 'nullable|string|max:255',
    ]);

    $contact = Contact::create($data);

    return response()->json([
        'message' => 'Contact created successfully',
        'contact' => $contact,
    ], 201);
});