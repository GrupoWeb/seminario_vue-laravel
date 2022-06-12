<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nombre' => 'Guatemala',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'El Progreso',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Sacatepéquez',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Chimaltenango',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Escuintla',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Santa Rosa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Sololá',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Totonicapán',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Quetzaltenango',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Suchitepéquez',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Retalhuleu',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'San Marcos',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Huehuetenango',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Quiché',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Baja Vera,paz',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Alta Vera,paz',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Petén',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Izabal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Zacapa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Chiquimula',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Jalapa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Jutiapa',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($data as $instituto) {
            Departamento::create($instituto);
        }
    }
}
