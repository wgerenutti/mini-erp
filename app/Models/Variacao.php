<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variacao extends Model
{

    protected $fillable = [
        'nome',
        'preco',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'variacoes';

    public function estoque()
    {
        return $this->hasOne(Estoque::class);
    }
}
