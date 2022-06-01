<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\userHasRoles;
use App\Models\RoleHierarchy;
use App\Models\Empresa;
use App\Models\Sede;
use App\Models\TipoPago;
use App\Models\TiposGasto;
use App\Models\Medida;
use App\Models\Productos;
use App\Models\StringCorrelativo;
use App\Models\Marca;
use App\Models\Linea;
use App\Models\Transmisiones;
use App\Models\TipoVehiculo;


class UsersAndNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusIds = array();

        $data = [
            [
                'name' => 'Usuario Extracción'
            ],
            [
                'name' => 'Admin Extracción'
            ],
            [
                'name' => 'Facturador Extracción'
            ],
            [
                'name' => 'Usuario Planta'
            ],
            [
                'name' => 'Admin Planta'
            ],
            [
                'name' => 'Facturador Planta'
            ],
            [
                'name' => 'Usuario Alquiler'
            ],
            [
                'name' => 'Admin Alquiler'
            ],
            [
                'name' => 'Facturador Alquiler'
            ],
            [
                'name' => 'Usuario Cemex'
            ],
            [
                'name' => 'Admin Cemex'
            ],
            [
                'name' => 'Facturador Cemex'
            ],
            [
                'name' => 'Usuario Transport'
            ],
            [
                'name' => 'Admin Transport'
            ],
            [
                'name' => 'Facturador Transport'
            ],
            [
                'name' => 'Usuario Constructora'
            ],
            [
                'name' => 'Admin Constructora'
            ],
            [
                'name' => 'Facturador Constructora'
            ],
        ];


        Empresa::create(['nombre'    =>  'TransPort Extracción']);
        Empresa::create(['nombre'    =>  'TransPort Planta']);
        Empresa::create(['nombre'    =>  'TransPort Alquiler']);
        Empresa::create(['nombre'    =>  'TransPort Cemex']);
        Empresa::create(['nombre'    =>  'Multi TrnasPort']);
        Empresa::create(['nombre'    =>  'TransPort Contructora']);



        /* Create roles */
        $adminRole = $roleAdmin = Role::create(['name' => 'admin']);
        RoleHierarchy::create([
            'role_id' => $adminRole->id,
            'hierarchy' => 1,
        ]);
        $userRole = Role::create(['name' => 'user']);
        RoleHierarchy::create([
            'role_id' => $userRole->id,
            'hierarchy' => 2,
        ]);
        $guestRole = Role::create(['name' => 'guest']); 
        RoleHierarchy::create([
            'role_id' => $guestRole->id,
            'hierarchy' => 3,
        ]);

        $empresaRole = $roleEmpresa = Role::create(['name' => 'facturador']); 
        RoleHierarchy::create([
            'role_id' => $empresaRole->id,
            'hierarchy' => 4,
        ]);


        foreach ($data as $key =>  $rolValue) {
            $valueRole = Role::create($rolValue);
            RoleHierarchy::create([
                'role_id' => $valueRole->id,
                'hierarchy' => $key,
            ]);
        }



        /*  insert status  */
        DB::table('status')->insert([
            'name' => 'Activo',
            'class' => 'primary',
        ]);


        array_push($statusIds, DB::getPdo()->lastInsertId());

        DB::table('status')->insert([
            'name' => 'Inactivo',
            'class' => 'secondary',
        ]);
        DB::table('status')->insert([
            'name' => 'Solicitado',
            'class' => 'primary',
        ]);
        DB::table('status')->insert([
            'name' => 'Aprobado',
            'class' => 'primary',
        ]);
        DB::table('status')->insert([
            'name' => 'Autorizado',
            'class' => 'primary',
        ]);
        DB::table('status')->insert([
            'name' => 'Despachado',
            'class' => 'primary',
        ]);
        DB::table('status')->insert([
            'name' => 'Facturado',
            'class' => 'primary',
        ]);



        /*  insert users   */
        $user = User::create([ 
            'name' => 'admin',
            'fullName'  =>  'Administrador',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'status_id' => 1
        ]);

        $user->assignRole('user');
        $user->assignRole($roleAdmin);
        userHasRoles::create([
            'role_id'   =>  $adminRole->id,
            'users_id'  =>  $user->id,
            'empresa_id'    =>  1
        ]);
        // userHasRoles::create([
        //     'role_id'   =>  $userRole->id,
        //     'users_id'  =>  $user->id,
        //     'empresa_id'    =>  2
        // ]);





        $user2 = User::create([ 
            'name' => 'jjolon',
            'fullName'  =>  'Juan José Jolón Granados',
            'email' => 'jjolon@miumg.edu.gt',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'status_id' => 1
        ]);
        $user2->assignRole('user');
        $user2->assignRole($roleAdmin);
        userHasRoles::create([
            'role_id'   =>  $adminRole->id,
            'users_id'  =>  $user2->id,
            'empresa_id'    =>  2
        ]);
        // userHasRoles::create([
        //     'role_id'   =>  $userRole->id,
        //     'users_id'  =>  $user2->id,
        //     'empresa_id'    =>  2
        // ]);



        
        Sede::create([
          'nombre'          =>  'Sede 1',
          'telefono'        =>  '5534-9970',
          'direccion'       =>  ' 1 av. 5-89 zona 8',
          'departamentos_id' =>  1,
          'municipio_id'    =>  1  
        ]);

        Sede::create([
          'nombre'          =>  'Sede 2',
          'telefono'        =>  '5534-9970',
          'direccion'       =>  ' 1 av. 5-89 zona 18',
          'departamentos_id' =>  1,
          'municipio_id'    =>  1  
        ]);

        TipoPago::create(['descripcion' =>  'Efectivo']);
        TipoPago::create(['descripcion' =>  'Credito']);
        TiposGasto::create(['descripcion' =>  'Pago clientes']);
        TiposGasto::create(['descripcion' =>  'Pago de planilla']);

        Medida::create(['nombre' =>  'Litro', 'status_id'  =>  1]);
        Medida::create(['nombre' =>  'Galon', 'status_id'   =>  1]);
        Medida::create(['nombre' =>  'Libra', 'status_id'  =>  1]);
        Medida::create(['nombre' =>  'Tonelada', 'status_id'   =>  1]);
        Medida::create(['nombre' =>  'Unidad', 'status_id'  =>  1]);
        Medida::create(['nombre' =>  'Docena', 'status_id'   =>  1]);

        Productos::create(['nombre' =>  'Cobre']);
        Productos::create(['nombre' =>  'Oro']);
        Productos::create(['nombre' =>  'Plata']);
        Productos::create(['nombre' =>  'Aluminio']);
        Productos::create(['nombre' =>  'Arcilla']);
        Productos::create(['nombre' =>  'Cuarzo']);
        Productos::create(['nombre' =>  'Zafiro']);
        Productos::create(['nombre' =>  'Esmeralda']);
        Productos::create(['nombre' =>  'Granito']);
        Productos::create(['nombre' =>  'Mármol']);
        Productos::create(['nombre' =>  'Mica']);



        StringCorrelativo::create(['correlativo' =>  'EMPRESA_1_']);
        StringCorrelativo::create(['correlativo' =>  'EMPRESA_2_']);
        StringCorrelativo::create(['correlativo' =>  'REQUISICION-EMP-']);


        Marca::create(['nombre' =>  'BMW']);
        Marca::create(['nombre' =>  'HONDA']);
        Linea::create(['nombre' =>  'I3']);
        Linea::create(['nombre' =>  'HR-V']);

        Transmisiones::create(['nombre' =>  'AUTOMATICA']);
        Transmisiones::create(['nombre' =>  'MANUAL']);
        TipoVehiculo::create(['nombre' =>  'SEDAN']);
        TipoVehiculo::create(['nombre' =>  'SUV']);
    }
}