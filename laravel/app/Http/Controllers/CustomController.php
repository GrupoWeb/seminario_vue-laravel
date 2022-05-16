<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Municipio;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Create Method get Departament
     * 
     * @return json
     */
    public function getDepartamentos(){
        $dep = Departamento::select('id','nombre')->get();

        return response()->json($dep, Response::HTTP_OK );
    }

    /**
     * Create Method get Departament by Id
     * 
     * @return json
     */
    public function getDepartamentosById(Request $request){

        $dep = Departamento::select('id','nombre')->where(['id' => $request->id])->get();

        return response()->json($dep, Response::HTTP_OK);
    }
    /**
     * Method update departament
     * 
     * @return json
     */
    public function updateDepartament(Request $request){

        $dep = Departamento::find($request->id);

        if($dep){
            $dep->update(['nombre'  =>  $request->nombre]);
        }
        
        return response()->json($dep, Response::HTTP_OK);
    }

    /**
     * Method delete departament
     * 
     * @return json
     */
    public function deleteDepartament(Request $request){

        $dep = Departamento::find($request->id);

        if($dep){
            $dep->delete();
        }
        
        return response()->json($dep, Response::HTTP_OK);
    }

    /**
     * Create Method get Municipio
     * 
     * @return json
     */
    public function getMunicipios(){
        $dep = Municipio::select('municipios.id','municipios.nombre', 'departamentos.nombre as departamento')
        ->join('departamentos','municipios.departamentos_id','=','departamentos.id')
        ->get();

        return response()->json($dep, Response::HTTP_OK );
    }

    /**
     * Create Method get Municipios by Id
     * 
     * @return json
     */
    public function getMunicipiosById(Request $request){

        $dep = Municipio::select('municipios.id','municipios.nombre', 'departamentos.nombre as departamento')
        ->join('departamentos','municipios.departamentos_id','=','departamentos.id')
        ->where(['municipios.id' => $request->id])
        ->get();


        return response()->json($dep, Response::HTTP_OK);
    }
    /**
     * Method update Municipio
     * 
     * @return json
     */
    public function updateMunicipio(Request $request){

        $dep = Municipio::find($request->id);

        if($dep){
            $dep->update(['nombre'  =>  $request->nombre]);
        }
        
        return response()->json($dep, Response::HTTP_OK);
    }

    /**
     * Method delete Municipio
     * 
     * @return json
     */
    public function deleteMunicipio(Request $request){

        $dep = Municipio::find($request->id);

        if($dep){
            $dep->delete();
        }
        
        return response()->json($dep, Response::HTTP_OK);
    }


}
