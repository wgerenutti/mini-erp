<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'cep',
        'endereco',
        'subtotal',
        'frete',
        'total',
    ];

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
