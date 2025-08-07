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
}
