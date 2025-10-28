<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;

class Comentario extends Model
{
    use HasFactory;

    function perteneceA()
    {
        return $this->belongsTo(Persona::class, 'dni', 'dni');
    }
}
