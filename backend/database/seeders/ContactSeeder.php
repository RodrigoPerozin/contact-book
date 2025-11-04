<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria 20 contatos de exemplo usando a factory
        Contact::factory()->count(20)->create();

        // Se você quiser criar manualmente alguns contatos específicos:
        /*Contact::create([
            'name' => 'Rodrigo D. Perozin',
            'email' => 'rodrigo@example.com',
            'phone' => '+55 47 99999-9999',
            'cep' => '01234-567',
            'city' => 'São Paulo',
            'country' => 'Brasil',
            'neighborhood' => 'Centro',
            'street_address' => 'Rua Exemplo',
            'house_number' => '123',
            'complement' => 's',
        ]);*/
    }
}
