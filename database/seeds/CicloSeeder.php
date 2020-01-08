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
        $data->ciclo = 2019;
        $data->actual = true;
        $data->inicio = '2019-01-08';
        $data->fin = '2019-10-24';
        $data->save();
    }
}
