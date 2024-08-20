<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'valor', 'imagem', 'estoque']; // Adicione 'estoque'
    
    public function produtos()
    {
       return $this->hasMany(ComandaProduto::class);
    }

}