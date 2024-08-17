<?php

// app/Models/Quarto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quarto extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_de_quarto', 'valor_hora', 'status_id'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function reservations()
    { 
        return $this->hasMany(Reservation::class);
    }
}
