<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crea 10 registros falsos en la tabla usuario
        Usuario::factory(10)->create();
    }
}
