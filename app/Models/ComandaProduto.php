<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComandaProduto extends Model
{
    use HasFactory;

    protected $fillable = [
        'comanda_id',
        'produto_id',
        'quantidade',
    ];

    public function comanda()
    {
        return $this->belongsTo(Comanda::class, 'comanda_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
