<?php

use Illuminate\Database\Seeder;
use App\Ciclo;

class CicloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Ciclo();
        $data->ciclo = 2021;
        $data->actual = true;
        $data->inicio = '2021-01-08';
        $data->fin = '2021-10-24';
        $data->save();
    }
}
