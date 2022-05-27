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
use App\Models\SedeEmpresa;
use App\Models\TipoPago;
use App\Models\TiposGasto;
use App\Models\Medida;
use App\Models\StringCorrelativo;
use App\Models\Correlativo;
use App\Models\Marca;
use App\Models\Linea;
use App\Models\Transmisiones;
use App\Models\TipoVehiculo;
use App\Models\Proveedores;
use App\Models\Clientes;
use App\Models\Productos;
use App\Models\Inventario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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

    public function associateSedes(){
        return response()->json(Sede::selectRaw('id as value, nombre as text')->whereNull('deleted_at')->get(),Response::HTTP_OK);
    }

    public function associateEmpresas(){
        return response()->json(Empresa::selectRaw('id as value, nombre as text')->whereNull('deleted_at')->get(),Response::HTTP_OK);
    }

    public function associateSedeEmpresa(Request $request){
        try {
            DB::beginTransaction();

            foreach ($request->empresa_id as $value) {
                $valid = SedeEmpresa::where(['sede_id'  => $request->sede_id, 'empresa_id'  => $value])->exists();
                if(!$valid){
                    $associate = SedeEmpresa::create([
                        'sede_id'       =>  $request->sede_id,
                        'empresa_id'    =>  $value
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success'  => true], Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error'    => $th],Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function getEmpresasAsociadas(Request $request){
        return response()->json(
            SedeEmpresa::selectRaw('empresas.id as value, empresas.nombre as name')->join('empresas','empresas.id','=','sede_empresas.empresa_id')
            ->where(['sede_empresas.sede_id' =>  $request->sede_id])->get(), Response::HTTP_OK);
    }

    public function getTipoPago(){
        return response()->json(TipoPago::selectRaw('id as value, descripcion as name')->whereNull('deleted_at')->get(), Response::HTTP_OK);
    }

    public function setTipoPago(Request $request){
        try {
            DB::beginTransaction();

            $tipo = TipoPago::create([
                'descripcion'   =>  $request->name
            ]);

            DB::commit();

            return response()->json($tipo, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTipoById(Request $request){
        return response()->json(TipoPago::select('descripcion as name')->where(['id'    => $request->tipo_id])->get(), Response::HTTP_OK);
    }

    public function updateTipoPagoById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = TipoPago::find($request->tipo_id);
            if($tipo){
                $tipo->update(['descripcion'    =>  $request->name]);
            }
            DB::commit();

            return response()->json($tipo,Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' . $th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteTipoById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = TipoPago::find($request->tipo_id);
            if($tipo){
                $tipo->delete();
            }

            DB::commit();
            return response()->json($tipo,Response::HTTP_OK);

        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' .$th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTipoGasto(){
        return response()->json(TiposGasto::selectRaw('id as value, descripcion as name')->whereNull('deleted_at')->get(), Response::HTTP_OK);
    }

    public function setTipoGasto(Request $request){
        try {
            DB::beginTransaction();

            $tipo = TiposGasto::create([
                'descripcion'   =>  $request->name
            ]);

            DB::commit();

            return response()->json($tipo, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTipoGastoById(Request $request){
        return response()->json(TiposGasto::select('descripcion as name')->where(['id'    => $request->tipo_id])->get(), Response::HTTP_OK);
    }
    
    public function deleteTipoGastoById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = TiposGasto::find($request->tipo_id);
            if($tipo){
                $tipo->delete();
            }

            DB::commit();
            return response()->json($tipo,Response::HTTP_OK);

        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' .$th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateTipoGastoById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = TiposGasto::find($request->tipo_id);
            if($tipo){
                $tipo->update(['descripcion'    =>  $request->name]);
            }
            DB::commit();

            return response()->json($tipo,Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' . $th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMedida(){
        return response()->json(Medida::selectRaw('id as value, nombre as name')->whereNull('deleted_at')->get(), Response::HTTP_OK);
    }

    public function setMedida(Request $request){
        try {
            DB::beginTransaction();

            $tipo = Medida::create([
                'nombre'   =>  $request->name,
                'status_id' =>  1
            ]);

            DB::commit();

            return response()->json($tipo, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMedidaById(Request $request){
        return response()->json(Medida::select('nombre as name')->where(['id'    => $request->tipo_id])->get(), Response::HTTP_OK);
    }
    
    public function deleteMedidaById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = Medida::find($request->tipo_id);
            if($tipo){
                $tipo->update(['status_id' =>  2]);
                $tipo->delete();
            }

            DB::commit();
            return response()->json($tipo,Response::HTTP_OK);

        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' .$th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateMedidaById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = Medida::find($request->tipo_id);
            if($tipo){
                $tipo->update(['nombre'    =>  $request->name]);
            }
            DB::commit();

            return response()->json($tipo,Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' . $th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStringCorrelativo(){
        return response()->json(
            StringCorrelativo::selectRaw('string_correlativos.id as value, ucase(string_correlativos.correlativo) as name, correlativos.numero , 
            correlativos.anio as year' )
            ->leftJoin('correlativos', 'correlativos.string_correlativo_id','string_correlativos.id')
            ->whereNull('string_correlativos.deleted_at')
            ->get(), Response::HTTP_OK);
    }

    public function setStringCorrelativo(Request $request){
        try {
            DB::beginTransaction();

            $tipo = StringCorrelativo::create([
                'correlativo'   =>  $request->name
            ]);

            DB::commit();

            return response()->json($tipo, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStringCorrelativoById(Request $request){
        return response()->json(StringCorrelativo::select('correlativo as name')->where(['id'    => $request->tipo_id])->get(), Response::HTTP_OK);
    }
    
    public function deleteStringCorrelativoById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = StringCorrelativo::find($request->tipo_id);
            if($tipo){
                $tipo->delete();
            }

            DB::commit();
            return response()->json($tipo,Response::HTTP_OK);

        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' .$th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStringCorrelativoById(Request $request){
        try {
            DB::beginTransaction();

            $tipo = StringCorrelativo::find($request->tipo_id);
            if($tipo){
                $tipo->update(['correlativo'    =>  $request->name]);
            }
            DB::commit();

            return response()->json($tipo,Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' . $th],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function setCorrelativoInitial(Request $request){
        try {
            DB::beginTransaction();

            $year = Carbon::now();

            $correlativo = Correlativo::create([
                'empresa_id'                =>  $request->empresa_id,
                'string_correlativo_id'     =>  $request->string_id,
                'numero'                    =>  1,
                'anio'                      =>  $year->isoFormat('YYYY')
            ]);


            DB::commit();

            return response()->json($correlativo, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error ' . $th], Response::HTTP_INSTERNAL_SERVER_ERROR);
        }
    }


    public function getData(Request $request){
        return response()->json(
            DB::table($request->model)->selectRaw('id as value, nombre as name')->whereNull('deleted_at')->get()
            , Response::HTTP_OK);
    }

    public function setData(Request $request){
        try {
            DB::beginTransaction();
            $dato = DB::table($request->model)->INSERT([
                'nombre'        =>  $request->name,
                'created_at'    =>  Carbon::now(),
                'updated_at'    =>  Carbon::now()
            ]);

            DB::commit();

            return response()->json($dato, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateData(Request $request){
        try {
            DB::beginTransaction();
            $dato = DB::table($request->model)->where(['id' =>  $request->id])->update([
                'nombre'        =>  $request->name,
            ]);

            DB::commit();

            return response()->json($dato, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDataById(Request $request){
        try {
            DB::beginTransaction();
            $dato = DB::table($request->model)->where(['id' =>  $request->id])->selectRaw('id as value, nombre as name')->get();

            DB::commit();

            return response()->json($dato, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUpdateDataById(Request $request){
        try {
            DB::beginTransaction();
            $dato = DB::table($request->model)->where(['id' =>  $request->id])->update(['nombre'    =>  $request->name]);

            DB::commit();

            return response()->json($dato, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getDeleteDataById(Request $request){
        try {
            DB::beginTransaction();
            $dato = DB::table($request->model)->where(['id' =>  $request->id])
            ->update(['deleted_at'    =>  Carbon::now()]);

            DB::commit();

            return response()->json($dato, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '   => $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function setProveedores(Request $request){
        try {
            DB::beginTransaction();

            $flag = Proveedores::where(['nit'   =>  $request->nit])->whereNull('deleted_at')->exists();

            if(!$flag){
                $prov = Proveedores::create([
                    'nombre'        =>  $request->nombre,
                    'nit'           =>  $request->nit,
                    'direccion'     =>  $request->direccion,
                    'telefono'      =>  $request->telefono,
                    'contacto'      =>  $request->contacto
                ]);
                DB::commit();
    
                return response()->json($prov, Response::HTTP_OK);
            }else{
                return response()->json(false, Response::HTTP_NO_CONTENT);
            }


        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProveedores(){
        return response()->json(
            Proveedores::selectRaw('id as value, nombre as text, nit, direccion as address, telefono as phone, contacto as contact')
            ->whereNull('deleted_at')->get(),
            Response::HTTP_OK
        );
    }



    public function getProveedoresById(Request $request){
        return response()->json(
            Proveedores::selectRaw('id as value, nombre as name, nit, direccion as address, telefono as phone, contacto as contact')
            ->whereNull('deleted_at')
            ->where(['id'   =>  $request->id])->get(),
            Response::HTTP_OK
        );
    }

    public function updateProveedores(Request $request){
        try {
            DB::beginTransaction();
            
            $prov = Proveedores::find($request->id);

            if($prov){
                $prov->update([
                    'nombre'        =>  $request->nombre,
                    'nit'           =>  $request->nit,
                    'direccion'     =>  $request->direccion,
                    'telefono'      =>  $request->telefono,
                    'contacto'      =>  $request->contacto
                ]);
            }

            DB::commit();

            return response()->json($prov, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProveedores(Request $request){
        try {
            DB::beginTransaction();
            
            $prov = Proveedores::find($request->id);

            if($prov){
                $prov->delete();
            }

            DB::commit();

            return response()->json($prov, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getClientes(){
        return response()->json(
            Clientes::selectRaw('id as value, nombre as name, nit, direccion as address, telefono as phone, email as correo')
            ->whereNull('deleted_at')->get(),
            Response::HTTP_OK
        );
    }


    public function setClientes(Request $request){
        try {
            DB::beginTransaction();

            $flag = Clientes::where(['nit'   =>  $request->nit])->whereNull('deleted_at')->exists();

            if(!$flag){
                $prov = Clientes::create([
                    'nombre'        =>  $request->nombre,
                    'nit'           =>  $request->nit,
                    'direccion'     =>  $request->direccion,
                    'telefono'      =>  $request->telefono,
                    'email'         =>  $request->contacto
                ]);
                DB::commit();
    
                return response()->json($prov, Response::HTTP_OK);
            }else{
                return response()->json(false, Response::HTTP_NO_CONTENT);
            }


        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getClienteById(Request $request){
        return response()->json(
            Clientes::selectRaw('id as value, nombre as name, nit, direccion as address, telefono as phone, email as correo')
            ->whereNull('deleted_at')
            ->where(['id'   =>  $request->id])->get(),
            Response::HTTP_OK
        );
    }

    public function deleteClientes(Request $request){
        try {
            DB::beginTransaction();
            
            $prov = Clientes::find($request->id);

            if($prov){
                $prov->delete();
            }

            DB::commit();

            return response()->json($prov, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();

            return response()->json(['error ' . $th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProductos(){
        return response()->json(Productos::selectRaw('id as value, nombre as text')->whereNull('deleted_at')->get(),Response::HTTP_OK);
    }

    public function getMedidas(){
        return response()->json(Medida::selectRaw('id as value, nombre as text')->whereNull('deleted_at')->get(),Response::HTTP_OK);
    }

    public function getSucursal(){
        return response()->json(SedeEmpresa::selectRaw('sede_empresas.id as value, concat(sedes.nombre,"-",empresas.nombre) as text')
        ->join('sedes', 'sedes.id','=','sede_empresas.sede_id')
        ->join('empresas','empresas.id','=','sede_empresas.empresa_id')
        ->whereNull('sede_empresas.deleted_at')->get(),Response::HTTP_OK);
    }

    public function setInventario(Request $request){
        
        $request->validate([
            'files'  =>  'required|mimes:jpeg,bmp,png|max:500'
        ]);

        try {
            DB::beginTransaction();

            $nomenclatura = "producto_". Carbon::now()->isoFormat('Y_m_d')."-". Carbon::now()->isoFormat('H_mm_ss'). ".".$request->file('files')->getClientOriginalExtension();
            $filePath = Storage::disk('productos')->putFileAs('productos',$request->file('files'), $nomenclatura);

            $inv = Inventario::create([
                'producto_id'       =>      $request->producto_id,
                'medida_id'         =>      $request->medida_id,
                'proveedores_id'    =>      $request->proveedores_id,
                'path_imagen'       =>      $filePath,
                'precio'            =>      (double)$request->precio,
                'stock'             =>      $request->stock,
                'sede_empresa_id'   =>      $request->sede_empresa_id

            ]);

            DB::commit();
            return response()->json($inv, Response::HTTP_OK);
            throw new Exception('Error al subir');

        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            if($filePath){
                Storage::delete($filePath);
            }
            return response()->json(['error '.$th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getInventario(){
        return response()->json(
            Inventario::selectRaw('inventarios.id as value,productos.nombre as producto_name,medidas.nombre as medida_name, proveedores.nombre as proveedor_name, inventarios.precio, inventarios.stock, sedes.nombre as sucursal_name')
            ->join('productos','productos.id','=','inventarios.producto_id')
            ->join('medidas','medidas.id','=','inventarios.medida_id')
            ->join('proveedores','proveedores.id','=','inventarios.proveedores_id')
            ->join('sede_empresas','sede_empresas.id','=','inventarios.sede_empresa_id')
            ->join('sedes','sedes.id','=','sede_empresas.id')
            ->whereNull('inventarios.deleted_at')
            ->get(),Response::HTTP_OK);
    }

    public function updateStock(Request $request){
        try {
            DB::beginTransaction();
            $up = Inventario::find($request->id);
            if($up){
                $up->update(['stock'    =>  ($request->stock + $request->update)]);
            }


            DB::commit();
            return response()->json($up, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '.$th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteStock(Request $request){
        try {
            DB::beginTransaction();
            $up = Inventario::find($request->id);
            if($up){
                $up->delete();
            }


            DB::commit();
            return response()->json($up, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['error '.$th], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
