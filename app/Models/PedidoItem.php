<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $fillable = [
        'pedido_id',
        'produto_id',
        'variacao_id',
        'quantidade',
        'preco_unit',
        'total_item',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pedido_itens';

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function variacao()
    {
        return $this->belongsTo(Variacao::class);
    }
}
