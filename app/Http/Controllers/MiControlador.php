<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Persona;
use App\Models\Propiedad;
use Illuminate\Http\Request;

class MiControlador extends Controller
{
        public function verPersonas() {
        $pers = Persona::all();

        return response()->json($pers,200);
    }


    //------------------------------------------------------------------------
    public function buscarPersona($dni) {
        //Opción A.
        //$pers = Persona::where('dni', '=', $dni)->get();
        //Opción B.
        $pers = Persona::find($dni);

        return response()->json($pers,200);
    }

    //------------------------------------------------------------------------
    public function insertarPersona(Request $req) {


        $pe = new Persona;

        //Opción A)
        // $pe->dni = $req->get('dni');
        // $pe->nombre = $req->get('nombre');
        // $pe->tfno = $req->get('tfno');
        // $pe->edad = $req->get('edad');

        try {
            //Opción A)
            // $pe->save();
            //Opción B)
            $pe = $pe->create($req->all());
            return response()->json($pe,201);
        } catch (\Exception $e) {
            $mensaje = 'Clave duplicada';
            return response()->json(['mens' => $mensaje],404);
        }

    }

    //------------------------------------------------------------------------
    public function insertarPropiedad(Request $req) {


        $pe = new Propiedad;

        try {
            $pe = $pe->create($req->all());
            return response()->json($pe,201);
        } catch (\Exception $e) {
            $mensaje = 'Clave duplicada';
            return response()->json($pe,404);
        }

    }

    //------------------------------------------------------------------------
    public function vermayores() {
        $pers = Persona::where('edad', '>', 18)
                ->orderBy('nombre', 'asc')
                ->get();

        return response()->json($pers,200);
    }


    //------------------------------------------------------------------------
    public function modificarPersona(Request $req, $dni) {
        $persona = Persona::find($dni);

        if ($persona) {
            $persona->update([
                'nombre' => $req->input('nombre'),
                'tfno'   => $req->input('tfno'),
                'edad'   => $req->input('edad')
            ]);

            return response()->json(['mensaje' => 'Persona modificada correctamente.'], 200);
        } else {
            return response()->json(['mensaje' => 'Persona no encontrada.'], 404);
        }
    }

    //------------------------------------------------------------------------
    public function borrarPersona($dni) {
        $persona = Persona::find($dni);

        if ($persona) {
            $persona->delete();
            return response()->json(['mensaje' => 'Persona eliminada correctamente.'], 200);
        } else {
            return response()->json(['mensaje' => 'Persona no encontrada.'], 404);
        }
    }

    //------------------------------------------------------------------------
    public function comentariosPersona($dni) {
        $pers = Persona::with('comentariosDe')->where('dni','=',$dni)->get();

        return response()->json($pers,200);
    }

    //------------------------------------------------------------------------
    public function mostrarComentarios() {
        $pers = Comentario::with('perteneceA')->get();

        return response()->json($pers,200);
    }

    //------------------------------------------------------------------------
    public function cochesDe($dni){
        $info = Propiedad::with(['infoCoche','infoPersona'])->where('dni',$dni)->get();
        // $primero = $info[0];

        return response()->json($info,200);
    }
}
