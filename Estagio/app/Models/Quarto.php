<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quarto extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'hora_entrada','tamanho', 'hora_contratada', 'hora_saida', 'status', 'cliente_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id')->withDefault([
            'nome' => 'N/A',
            'cpf' => 'N/A',
        ]);
    }
}
