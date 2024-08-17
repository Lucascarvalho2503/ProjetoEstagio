<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'cpf', 'celular'];

    // Add relationship with Reservation
    public function reservations()
    {
       return $this->hasMany(Reservation::class);
    }

}
