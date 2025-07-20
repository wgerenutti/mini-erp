<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome', 'preco', 'created_by', 'updated_by'];


    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }
    public function estoque()
    {
        return $this->hasOne(Estoque::class);
    }
}
