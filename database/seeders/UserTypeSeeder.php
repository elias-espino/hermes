<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder {
    public function run() {
        DB::table('user_types')->insert([
            ['id' => 1, 'name' => 'Admin', 'description' => 'Acceso total al sistema'],
            ['id' => 2, 'name' => 'Manager', 'description' => 'Puede crear empresas y administrar usuarios de sucursal'],
            ['id' => 3, 'name' => 'SucursalUser', 'description' => 'Acceso limitado a una sucursal según permisos asignados']
        ]);
    }
}
//php artisan db:seed --class=UserTypeSeeder
