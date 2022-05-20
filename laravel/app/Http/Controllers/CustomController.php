<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Municipio;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Sede;
use App\Models\Empresa;

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
        $dep = Departamento::select('id as value','nombre as text')->get();

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
        $dep = Municipio::select('municipios.id as value','municipios.nombre as text', 'departamentos.nombre as departamento')
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


    /**
     * Methods for Sedes
    */

    public function getSede(){
        $sede = Sede::selectRaw('sedes.id as value, sedes.nombre as name, sedes.telefono as number, sedes.direccion as address, dep.nombre as departament, muni.nombre as municipality')
            ->join('departamentos as dep','dep.id','=','sedes.departamentos_id')
            ->join('municipios as muni','muni.id','=','sedes.municipio_id')
            ->whereNull('sedes.deleted_at')
            ->get();

        return response()->json($sede, Response::HTTP_OK);
    }

    /**
     *  method add sede
     * 
     * @return Json Object
     */

     public function setSede(Request $request){

         try {
             DB::beginTransaction();

             $sede = Sede::create([
                 'nombre'           =>  $request->name,
                 'telefono'         =>  $request->phone,
                 'direccion'        =>  $request->address,
                 'departamentos_id' =>  $request->departamento_id,
                 'municipio_id'     =>  $request->municipio_id
             ]);

             DB::commit();

             return response()->json(['success' =>  $sede], Response::HTTP_OK);
         } catch (\Throwable $th) {
             throw $th;
             DB::rollBack();

             return response()->json(['error'   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
         }
     }

     public function getMunicipioByIdSede(Request $request){
         return response()->json(Municipio::selectRaw('id as value, nombre as text')->where(['departamentos_id' =>  $request->id])->get(), Response::HTTP_OK); 
     }

    public function getSedeById(Request $request){
        return response()->json(
            Sede::selectRaw('sedes.id as value, sedes.nombre as name, sedes.telefono as number, sedes.direccion as address, dep.id as departament, muni.id as municipality')
            ->join('departamentos as dep','dep.id','=','sedes.departamentos_id')
            ->join('municipios as muni','muni.id','=','sedes.municipio_id')
            ->where(['sedes.id' => $request->id])
            ->get(),Response::HTTP_OK);
    }

    public function updateSedeById(Request $request){
        try {
            DB::beginTransaction();

            $sede = Sede::find($request->id);
            if($sede){
                $sede->update([
                    'nombre'           =>  $request->name,
                    'telefono'         =>  $request->phone,
                    'direccion'        =>  $request->address,
                    'departamentos_id' =>  $request->departamento_id,
                    'municipio_id'     =>  $request->municipio_id
                ]);
            }


            DB::commit();

            return response()->json($sede, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error'    =>  $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSedeById(Request $request){
        try {
            DB::beginTransaction();

            $sede = Sede::find($request->id);

            if($sede){
                $sede->delete();
            }

            DB::commit();

            return response()->json($sede, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json('failed ' .$th ,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEmpresas(){
        return response()->json(Empresa::selectRaw('id as value, nombre as text')->whereNull('deleted_at')->get(),Response::HTTP_OK);
    }

    public function setEmpresa(Request $request){
        try {
            DB::beginTransaction();

            $empresa = Empresa::create([
                'nombre'    =>  $request->name
            ]);

            DB::commit();

            return response()->json($empresa, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEmpresaById(Request $request){
        return response()->json(
            Empresa::selectRaw('id as value, nombre as name')->where(['id'  =>  $request->id])->get(),Response::HTTP_OK
        );
    }

    public function updateEmpresa(Request $request){
        try {
            DB::beginTransaction();

            $empresa = Empresa::find($request->id);
            if($empresa){
                $empresa->update(['nombre'  =>  $request->name]);
            }

            DB::commit();

            return response()->json($empresa,Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteEmpresaById(Request $request){
        try {
            DB::beginTransaction();

            $empresa = Empresa::find($request->id);
            if($empresa){
                $empresa->delete();
            }
            DB::commit();

            return response()->json($empresa, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    

}
