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
        $in = json_decode($this->request->getContent());
        if(!$in){
            return response()->json(array(
                "result" => false,
                "code" => 'invalid_param',
                'line' => __line__
            ), 200);
        }
        $params = array('propietario_cedula' => null, 'propietario_nombres'  => null, 'tipo'  => null, 'marca'  => null, 'placa'  => null);
        $valid_request = true;
        foreach ($params as $key => $value) {
            if(!property_exists($in, $key)){
                $valid_request = false;
                continue;
            }
            $params[$key] = $in->$key;
        }
        if(!$valid_request){
            return response()->json(array(
                "result" => false,
                "code" => "invalid_param",
                'line' => __line__
            ), 200);
        }
        $validator = \Validator::make($params, [
            "propietario_cedula" => 'required|numeric',
            "propietario_nombres" => 'required|string|max:255',
            "tipo" => 'required|numeric',
            "marca"=>'required|numeric',
            "placa"=>'required|string|max:20'
        ]);
        if($validator->fails()){
            return response()->json(array(
                "result" => false,
                "code" => "invalid_param",
                'line' => __line__
            ), 200);
        }
        
        $propietario = Propietarios_vehiculos::where('cedula', (int) $params["propietario_cedula"])->first();
        if(!$propietario){
            $propietario = new Propietarios_vehiculos;
            $propietario->nombres = $params["propietario_nombres"];
            $propietario->cedula = $params["propietario_cedula"];
            $propietario->save();   
        }

        $marca = Marcas_vehiculos::find((int) $params["marca"]);
        if(!$marca){
            return response()->json(array(
                "result" => false,
                "code" => "invalid_marca",
                'line' => __line__
            ), 200);
        }

        $tipo = Tipos_vehiculos::find((int) $params["tipo"]);
        if(!$tipo){
            return response()->json(array(
                "result" => false,
                "code" => "invalid_tipo",
                'line' => __line__
            ), 200);
        }
        
        $vehiculo = Vehiculos::where('placa', (string) $params["placa"])->first();
        if($vehiculo){
            return response()->json(array(
                "result" => false,
                "code" => "vehiculo_exist",
                'line' => __line__
            ), 200);
        }

        $vehiculo = new Vehiculos;
        $vehiculo->tipo_vehiculo_id = $tipo->id;
        $vehiculo->marca_vehiculo_id = $marca->id;
        $vehiculo->propietario_vehiculo_id = $propietario->id;
        $vehiculo->placa = strtoupper($params["placa"]);
        if($vehiculo->save()){
            return response()->json(array(
                "result" => true,
                "data" => array(
                    "propietario" => ucwords($vehiculo->propietario_vehiculo->nombres),
                    "tipo" => $vehiculo->tipo_vehiculo->nombre,
                    "marca" => ucfirst(strtolower($vehiculo->marca_vehiculo->nombre)),
                    "placa" => $vehiculo->placa,
                )
            ), 200);
        }
        
        return response()->json(array(
            "result" => false,
            "code" => "error_save",
            'line' => __line__
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
            $validator = \Validator::make([
                "placa" => $this->request->placa
            ], [
                "placa"=>'required|string|max:20'
            ]);
            if($validator->fails()){
                return response()->json(array(
                    "result" => false,
                    "code" => "invalid_placa"
                ), 200);
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
            $validator = \Validator::make([
                "propietario_cedula" => $this->request->propietario_cedula
            ], [
                "propietario_cedula" => 'required|numeric',
            ]);
            if($validator->fails()){
                return response()->json(array(
                    "result" => false,
                    "code" => "invalid_propietario_cedula"
                ), 200);
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
            $validator = \Validator::make([
                "propietario_nombres" => $this->request->propietario_nombres
            ], [
                "propietario_nombres" => 'required|string|max:255',
            ]);
            if($validator->fails()){
                return response()->json(array(
                    "result" => false,
                    "code" => "invalid_propietario_nombres"
                ), 200);
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
