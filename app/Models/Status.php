<?php

// app/Models/Status.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function quartos()
    {
        return $this->hasMany(Quarto::class);
    }
}