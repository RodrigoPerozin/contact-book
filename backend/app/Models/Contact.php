<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // Nome da tabela (opcional, só se não seguir o padrão 'contacts')
    // protected $table = 'contacts';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cep',
        'address1',
        'address2',
    ];

    // Conversões de tipos automáticas
    protected $casts = [
        // 'email_verified_at' => 'datetime', // só se você tiver esse campo
    ];
}
