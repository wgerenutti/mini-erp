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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cupons()
    {
        return $this->belongsToMany(Cupom::class, 'pedido_cupons', 'pedido_id', 'cupom_id')
            ->withPivot('desconto_aplicado')
            ->withTimestamps();
    }
}
