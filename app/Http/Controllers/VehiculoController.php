<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Marcas_vehiculos;
use App\Models\Propietarios_vehiculos;
use App\Models\Tipos_vehiculos;
use App\Models\Vehiculos;

class VehiculoController extends Controller
{
    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * Create a vehicle
     *
     * @param  string  $propietario_cedula
     * @param  string  $propietario_nombres
     * @param  int  $tipo
     * @param  int  $marca
     * @param  int  $placa
     * @return JsonResponse
     */
    public function createVehiculo()
    {
        try {
            $this->request->validate([
                "propietario_cedula" => 'required|numeric',
                "propietario_nombres" => 'required|string|max:255',
                "tipo" => 'required|numeric',
                "marca"=>'required|numeric',
                "placa"=>'required|string|max:20'
            ]);
        } catch (\Throwable $th) {
            return response()->json(array(
                "result" => false,
                "code" => "invalid_param"
            ));
        }
        
        $propietario = Propietarios_vehiculos::where('cedula', (int) $this->request->propietario_cedula)->first();
        if(!$propietario){
            $propietario = new Propietarios_vehiculos;
            $propietario->nombres = $this->request->propietario_nombres;
            $propietario->cedula = $this->request->propietario_cedula;
            $propietario->save();   
        }

        $marca = Marcas_vehiculos::find((int) $this->request->marca);
        if(!$marca){
            return response()->json(array(
                "result" => false,
                "code" => "invalid_marca"
            ));
        }

        $tipo = Tipos_vehiculos::find((int) $this->request->tipo);
        if(!$tipo){
            return response()->json(array(
                "result" => false,
                "code" => "invalid_tipo"
            ));
        }
        
        $vehiculo = Vehiculos::where('placa', (string) $this->request->placa)->first();
        if($vehiculo){
            return response()->json(array(
                "result" => false,
                "code" => "vehiculo_exist"
            ));
        }

        $vehiculo = new Vehiculos;
        $vehiculo->tipo_vehiculo_id = $tipo->id;
        $vehiculo->marca_vehiculo_id = $marca->id;
        $vehiculo->propietario_vehiculo_id = $propietario->id;
        $vehiculo->placa = strtoupper($this->request->placa);
        if($vehiculo->save()){
            return response()->json(array(
                "result" => true,
                "data" => array(
                    "propietario" => ucwords($vehiculo->propietario_vehiculo->nombres),
                    "tipo" => $vehiculo->tipo_vehiculo->nombre,
                    "marca" => ucfirst(strtolower($vehiculo->marca_vehiculo->nombre)),
                    "placa" => $vehiculo->placa,
                )
            ));
        }
        
        return response()->json(array(
            "result" => false,
            "code" => "error_save"
        ));

    }

    /**
     * Search a vehicle
     *
     * @param  string  $placa
     * @param  string  $propietario_cedula
     * @param  string  $propietario_nombres
     * @return JsonResponse
     */
    public function findVehiculo(){
        if($this->request->placa){
            try {
                $this->request->validate([
                    "placa"=>'required|string|max:20'
                ]);
            } catch (\Throwable $th) {
                return response()->json(array(
                    "result" => false,
                    "code" => "invalid_placa"
                ));
            }
            $vehiculo = Vehiculos::where('placa', (string) $this->request->placa)->first();
            if($vehiculo){
                return response()->json(array(
                    "result" => false,
                    "data" => array(
                        "propietario" => array(
                            "nombres" => ucwords($vehiculo->propietario_vehiculo->nombres),
                            "cedula" => $vehiculo->propietario_vehiculo->cedula
                        ),
                        "vehiculos" => array(
                            array(
                                "tipo" => $vehiculo->tipo_vehiculo->nombre,
                                "marca" => ucfirst(strtolower($vehiculo->marca_vehiculo->nombre)),
                                "placa" => $vehiculo->placa,
                            )
                        )
                    )
                )); 
            }

            return response()->json(array(
                "result" => false,
                "code" => "vehiculo_no_exist"
            ));
            
        }else if($this->request->propietario_cedula){
            try {
                $this->request->validate([
                    "propietario_cedula" => 'required|numeric',
                ]);
            } catch (\Throwable $th) {
                return response()->json(array(
                    "result" => false,
                    "code" => "invalid_cedula"
                ));
            }
            $propietario = Propietarios_vehiculos::where('cedula', (int) $this->request->propietario_cedula)->first();
            if($propietario){
                $vehiculos = [];
                foreach ($propietario->vehiculos as $vehiculo) {
                    $vehiculos[] = array(
                        "tipo" => $vehiculo->tipo_vehiculo->nombre,
                        "marca" => ucfirst(strtolower($vehiculo->marca_vehiculo->nombre)),
                        "placa" => $vehiculo->placa,
                    );
                }
                return response()->json(array(
                    "result" => false,
                    "data" => array(
                        "propietario" => array(
                            "nombres" => ucwords($propietario->nombres),
                            "cedula" => $propietario->cedula
                        ),
                        "vehiculos" => $vehiculos
                    )
                )); 
            }

            return response()->json(array(
                "result" => false,
                "code" => "propietario_no_exist"
            ));
        }else if($this->request->propietario_nombres){
            try {
                $this->request->validate([
                    "propietario_nombres" => 'required|string|max:255',
                ]);
            } catch (\Throwable $th) {
                return response()->json(array(
                    "result" => false,
                    "code" => "invalid_nombre"
                ));
            }
            $propietario = Propietarios_vehiculos::where('nombres', 'like', (string) '%'.$this->request->propietario_nombres.'%')->first();
            if($propietario){
                $vehiculos = [];
                foreach ($propietario->vehiculos as $vehiculo) {
                    $vehiculos[] = array(
                        "tipo" => $vehiculo->tipo_vehiculo->nombre,
                        "marca" => ucfirst(strtolower($vehiculo->marca_vehiculo->nombre)),
                        "placa" => $vehiculo->placa,
                    );
                }
                return response()->json(array(
                    "result" => false,
                    "data" => array(
                        "propietario" => array(
                            "nombres" => ucwords($propietario->nombres),
                            "cedula" => $propietario->cedula
                        ),
                        "vehiculos" => $vehiculos
                    )
                )); 
            }
            return response()->json(array(
                "result" => false,
                "code" => "propietario_no_exist"
            ));
        }
        return response()->json(array(
            "result" => false,
            "code" => "invalid_param"
        ));
    }

    

}
