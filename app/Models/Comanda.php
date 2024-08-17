<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'status',
        'valor_comanda',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function produtos()
    {
        return $this->hasMany(ComandaProduto::class);
    }
}
