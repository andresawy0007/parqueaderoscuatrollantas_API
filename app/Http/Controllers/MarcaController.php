<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Marcas_vehiculos;

class MarcaController extends Controller
{
    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * Show num vehicle per branch
     *
     * @return JsonResponse
     */
    public function vehiculosPorMarcas(){
        $marcas = Marcas_vehiculos::all();

        $out = [];

        foreach ($marcas as $marca) {
            $out[] = array(
                "id" => $marca->id,
                "nombre" => ucfirst(strtolower($marca->nombre)),
                "numero_vehiculos" => $marca->vehiculos->count(),
            );
        }

        return response()->json(array(
            "result" => true,
            "data" => $out
        ));
    }
}
