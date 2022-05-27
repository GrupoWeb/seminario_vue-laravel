<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//use database\seeds\NotesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call([
            FolderTableSeeder::class,
            DepartamentosSeeder::class,
            MunicipioSeeder::class,
            UsersAndNotesSeeder::class,
            MenusTableSeeder::class,
        ]);
    }
}
