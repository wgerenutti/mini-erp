<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Cupom extends Model
{
    protected $table = 'cupons';

    protected $fillable = [
        'created_by',
        'updated_by',
        'codigo',
        'valor_desc',
        'pct_desc',
        'minimo',
        'valid_from',
        'valid_to',
        'validade',
        'uso_maximo',
        'ativo',
    ];

    protected $casts = [
        'valor_desc' => 'float',
        'pct_desc'   => 'float',
        'minimo'     => 'float',
        'valid_from' => 'datetime',
        'valid_to'   => 'datetime',
        'validade'   => 'date',
        'uso_maximo' => 'integer',
        'ativo'      => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('ativo', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('valid_to')->orWhere('valid_to', '>=', $now);
            });
    }

    public function pedidos()
    {
        return $this->belongsToMany(
            Pedido::class,
            'pedido_cupons',
            'cupom_id',
            'pedido_id'
        )->withTimestamps();
    }

    public function isActiveNow(): bool
    {
        $now = Carbon::now();
        if (! $this->ativo) return false;
        if ($this->validade && $now->gt(Carbon::parse($this->validade)->endOfDay())) return false;
        if ($this->valid_from && $now->lt(Carbon::parse($this->valid_from))) return false;
        if ($this->valid_to && $now->gt(Carbon::parse($this->valid_to))) return false;
        if ($this->uso_maximo !== null && $this->uso_count >= $this->uso_maximo) return false;
        return true;
    }

    public function isValidForSubtotal(float $subtotal): bool
    {
        return $subtotal >= (float)$this->minimo;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->valor_desc) {
            $discount = min((float)$this->valor_desc, $subtotal);
        } elseif ($this->pct_desc) {
            $discount = round($subtotal * ((float)$this->pct_desc / 100), 2);
        } else {
            $discount = 0.0;
        }
        return round($discount, 2);
    }

    public function aplicarCupom(Request $request)
    {
        $data = $request->validate(['codigo' => 'required|string']);
        $codigo = trim($data['codigo']);

        $cupom = Cupom::whereRaw('LOWER(codigo) = ?', [strtolower($codigo)])->first();

        if (! $cupom) {
            return response()->json(['error' => 'Cupom não encontrado'], 404);
        }

        $items = session('carrinho.items', []);
        $subtotal = array_sum(array_map(fn($i) => $i['preco'] * $i['quantidade'], $items));

        if (! $cupom->isActiveNow()) {
            return response()->json(['error' => 'Cupom inválido ou expirado'], 422);
        }

        if (! $cupom->isValidForSubtotal($subtotal)) {
            return response()->json(['error' => 'Pedido não alcança o valor mínimo para este cupom'], 422);
        }

        $desconto = $cupom->calculateDiscount($subtotal);

        // grava na sessão
        $carrinho = session('carrinho', [
            'items' => $items,
            'subtotal' => $subtotal,
            'frete' => 0,
            'total' => 0,
        ]);

        $carrinho['cupom'] = [
            'id' => $cupom->id,
            'codigo' => $cupom->codigo,
            'desconto_aplicado' => $desconto,
        ];

        session(['carrinho' => $carrinho]);

        // re-sincroniza (vai recalcular frete e total com o cupom)
        $this->syncCartSession($carrinho['items'], session('carrinho.cep'), session('carrinho.endereco'));

        $cartHtml = view('partials.cart_body')->render();

        return response()->json([
            'count' => count(session('carrinho.items', [])),
            'cart_html' => $cartHtml,
        ], 200);
    }

    public function removerCupom()
    {
        $carrinho = session('carrinho', []);
        if (isset($carrinho['cupom'])) {
            unset($carrinho['cupom']);
            session(['carrinho' => $carrinho]);
            $this->syncCartSession($carrinho['items'] ?? [], $carrinho['cep'] ?? null, $carrinho['endereco'] ?? null);
        }

        $cartHtml = view('partials.cart_body')->render();
        return response()->json([
            'count' => count(session('carrinho.items', [])),
            'cart_html' => $cartHtml,
        ], 200);
    }
}
