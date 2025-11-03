<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Contact;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/contact', function (Request $request) {
    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:contacts,email',
        'phone'    => 'nullable|string|max:20',
        'cep'      => 'nullable|string|max:10',
        'address1' => 'nullable|string|max:255',
        'address2' => 'nullable|string|max:255',
    ]);

    $contact = Contact::create($data);

    return response()->json([
        'message' => 'Contact created successfully',
        'contact' => $contact,
    ], 201);
});