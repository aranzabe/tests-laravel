<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comentario;

class Persona extends Model
{
    /** @use HasFactory<\Database\Factories\PersonaFactory> */
    use HasFactory;

     //protected $table = 'personas'; //Por defecto tomaría la tabla 'personas'.
    protected $primaryKey = 'dni';  //Por defecto el campo clave es 'id', entero y autonumérico.
    public $incrementing = false; //Para indicarle que la clave no es autoincremental.
    protected $keyType = 'string';   //Indicamos que la clave no es entera.
    public $timestamps = false;   //Con esto Eloquent no maneja automáticamente created_at ni updated_at.

    protected $fillable = ['dni', 'nombre', 'tfno', 'edad'];  //Campos que se rellenarán de forma masiva para su inserción.
    protected $hidden = [];  //Atributos que no se convertirán en json cuando se conviertan los objetos para su serialización con response()->json(....)

    function comentariosDe(){
        return $this->hasMany(Comentario::class,'dni','dni');
    }
}
