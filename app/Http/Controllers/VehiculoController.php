<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * Create a vehicle
     *
     * @param  int  $propietario
     * @param  int  $tipo
     * @param  int  $marca
     * @param  int  $placa
     * @return \Illuminate\Http\Response
     */
    public function createVehiculo(){
        $validate = $this->validate($this->request, [
            "propietario" => 'required|numeric',
            "tipo" => 'required|string|max:100',
            "marca"=>'required|string|max:100',
            "placa"=>'required|string|max:20'
        ]);
        if($validate->fails()){
            $arr_error = array(
                "result" => false,
                "code" => "invalid_param"
            );
            return response()->json(array(
                "result" => false,
                "code" => "invalid_param"
            ));
        }
    }

}
