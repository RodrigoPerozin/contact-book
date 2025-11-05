<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactsController
{
    public function create(Request  $request) {

        try {

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
                'picture'       => 'nullable|string',
                'state'         => 'nullable|string|max:100',
            ]);

            $contact = Contact::create($data);

            return response()->json([
                'message' => 'Contato criado com sucesso!',
                'contact' => $contact,
            ], 201);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível criar o contato',
                'error' => $th->getMessage(),
            ], 500);

        }

    }

    public function find(Request $request) {
        
        try {

            $query = Contact::query();

            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }

            if ($request->has('email')) {
                $query->where('email', 'like', '%' . $request->input('email') . '%');
            }

            if ($request->has('phone')) {
                $query->where('phone', 'like', '%' . $request->input('phone') . '%');
            }

            if ($request->has('city')) {
                $query->where('city', 'like', '%' . $request->input('city') . '%');
            }

            if ($request->has('country')) {
                $query->where('country', 'like', '%' . $request->input('country') . '%');
            }

            if ($request->has('cep')) {
                $query->where('cep', 'like', '%' . $request->input('cep') . '%');
            }

            if ($request->has('neighborhood')) {
                $query->where('neighborhood', 'like', '%' . $request->input('neighborhood') . '%');
            }

            if ($request->has('street_address')) {
                $query->where('street_address', 'like', '%' . $request->input('street_address') . '%');
            }

            if ($request->has('house_number')) {
                $query->where('house_number', 'like', '%' . $request->input('house_number') . '%');
            }

            if ($request->has('complement')) {
                $query->where('complement', 'like', '%' . $request->input('complement') . '%');
            }

            if ($request->has('id')) {
                $query->where('id', $request->input('id'));
            }

            $perPage = $request->input('per_page', 20);
            $contacts = $query->paginate($perPage);
            return response()->json($contacts);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível buscar o(s) contato(s)',
                'error' => $th->getMessage()
            ],500);

        }

    }

    public function delete($id) {
        try{

            $contact = Contact::find($id);
            if ($contact) {
                $contact->delete();
                return response()->json(['message' => 'Contact deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Contact not found'], 404);
            }

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Erro ao deletar o contato',
                'error' => $th->getMessage()
            ],500);

        }

    }

    public function update(Request $request, $id) {
        try {

            $contact = Contact::find($id);
            if (!$contact) {
                return response()->json(['message' => 'Contact not found'], 404);
            }

            $data = $request->validate([
                'name'           => 'sometimes|required|string|max:255',
                'email'          => 'sometimes|required|email|unique:contacts,email,' . $id,
                'phone'          => 'sometimes|nullable|string|max:20',
                'cep'            => 'sometimes|nullable|string|max:10',
                'city'           => 'sometimes|nullable|string|max:100',
                'country'        => 'sometimes|nullable|string|max:100',
                'neighborhood'   => 'sometimes|nullable|string|max:100',
                'street_address' => 'sometimes|nullable|string|max:255',
                'house_number'   => 'sometimes|nullable|string|max:20',
                'complement'     => 'sometimes|nullable|string|max:255',
            ]);

            $contact->update($data);

            return response()->json([
                'message' => 'Contato atualizado com sucesso!',
                'contact' => $contact,
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Erro ao atualizar o contato',
                'error' => $th->getMessage()
            ],500);

        }
    }

    public function defaultPicture () {
        
        $path = public_path('images/default-user.png');
        
        if (!file_exists($path)) {
            return response()->json([
                'error' => 'Imagem padrão não encontrada.'
            ], 404);
        } 

        $defaultImage = base64_encode(file_get_contents($path));
        $base64Bytes = strlen($defaultImage);
        $base64KB = round($base64Bytes / 1024, 2);
        
        return response()->json([
            'image' => 'data:image/png;base64,' . $defaultImage,
            'size_in_kb' => $base64KB
        ], 200);
    }

}
