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



        /*  insert users   */
        $user = User::create([ 
            'name' => 'admin',
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
            'users_id'  =>  $user->id
        ]);
        userHasRoles::create([
            'role_id'   =>  $userRole->id,
            'users_id'  =>  $user->id
        ]);





        $user2 = User::create([ 
            'name' => 'jjolon',
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
            'users_id'  =>  $user2->id
        ]);
        userHasRoles::create([
            'role_id'   =>  $userRole->id,
            'users_id'  =>  $user2->id
        ]);
    }
}