<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quarto extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_de_quarto', 'valor_hora', 'status_id', 'numero'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'quarto_id');
    }

    public function currentReservation()
    {
        return $this->hasOne(Reservation::class)->where('status', 'open');
    }


}
