<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Produto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produtos';

    protected $fillable = ['nome', 'preco', 'created_by', 'updated_by'];


    public static function booted()
    {
        static::creating(function ($produto) {
            if (Auth::check()) {
                $produto->created_by = Auth::id();
                $produto->updated_by = Auth::id();
            }
        });

        static::updating(function ($produto) {
            if (Auth::check()) {
                $produto->updated_by = Auth::id();
            }
        });
    }

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }
    public function estoque()
    {
        return $this->hasOne(Estoque::class);
    }
}
