<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'quarto_id',
        'horario_entrada',
        'horas_contratadas',
        'valor_final',
        'horario_saida'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function quarto()
    {
        return $this->belongsTo(Quarto::class);
    }
}

