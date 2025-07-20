<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estoque';

    protected $fillable = ['quantidade'];
}
