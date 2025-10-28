<?php

use App\Http\Controllers\MiControlador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('verpersonas',[MiControlador::class, 'verPersonas']);
Route::get('buscarpersona/{dni}',[MiControlador::class, 'buscarPersona']);
Route::get('vermayores',[MiControlador::class, 'vermayores']);
Route::post('insertarpersona',[MiControlador::class, 'insertarPersona']);
Route::post('insertarpropiedad',[MiControlador::class, 'insertarPropiedad']);
Route::delete('borrarpersona/{dni}',[MiControlador::class, 'borrarPersona']);
Route::put('modificarpersona/{dni}',[MiControlador::class, 'modificarPersona']);

Route::get('comentariospersona/{dni}',[MiControlador::class, 'comentariosPersona']);
Route::get('mostrarcomentarios',[MiControlador::class, 'mostrarComentarios']);
Route::get('cochesde/{dni}',[MiControlador::class, 'cochesDe']);
