<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Rol;
        $data->rol = 'admin';
        $data->save();

        $data = new Rol;
        $data->rol = 'operario';
        $data->save();
    }
}
