<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'email',
        'documento',
        'tipo',
        'telefone',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
