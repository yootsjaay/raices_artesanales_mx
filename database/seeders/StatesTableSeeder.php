<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $states = [
        ['name' => 'Aguascalientes', 'abbreviation' => 'AG'],
        ['name' => 'Baja California', 'abbreviation' => 'BC'],
        ['name' => 'Baja California Sur', 'abbreviation' => 'BS'],
        ['name' => 'Campeche', 'abbreviation' => 'CM'],
        ['name' => 'Chiapas', 'abbreviation' => 'CS'],
        ['name' => 'Chihuahua', 'abbreviation' => 'CH'],
        ['name' => 'Ciudad de México', 'abbreviation' => 'CX'],
        ['name' => 'Coahuila', 'abbreviation' => 'CO'],
        ['name' => 'Colima', 'abbreviation' => 'CL'],
        ['name' => 'Distrito Federal', 'abbreviation' => 'DF'],
        ['name' => 'Durango', 'abbreviation' => 'DG'],
        ['name' => 'Guanajuato', 'abbreviation' => 'GT'],
        ['name' => 'Guerrero', 'abbreviation' => 'GR'],
        ['name' => 'Hidalgo', 'abbreviation' => 'HG'],
        ['name' => 'Jalisco', 'abbreviation' => 'JA'],
        ['name' => 'México', 'abbreviation' => 'EM'],
        ['name' => 'Michoacán', 'abbreviation' => 'MI'],
        ['name' => 'Morelos', 'abbreviation' => 'MO'],
        ['name' => 'Nayarit', 'abbreviation' => 'NA'],
        ['name' => 'Nuevo León', 'abbreviation' => 'NL'],
        ['name' => 'Oaxaca', 'abbreviation' => 'OA'],
        ['name' => 'Puebla', 'abbreviation' => 'PU'],
        ['name' => 'Querétaro', 'abbreviation' => 'QT'],
        ['name' => 'Quintana Roo', 'abbreviation' => 'QR'],
        ['name' => 'San Luis Potosí', 'abbreviation' => 'SL'],
        ['name' => 'Sinaloa', 'abbreviation' => 'SI'],
        ['name' => 'Sonora', 'abbreviation' => 'SO'],
        ['name' => 'Tabasco', 'abbreviation' => 'TB'],
        ['name' => 'Tamaulipas', 'abbreviation' => 'TM'],
        ['name' => 'Tlaxcala', 'abbreviation' => 'TL'],
        ['name' => 'Veracruz', 'abbreviation' => 'VE'],
        ['name' => 'Yucatán', 'abbreviation' => 'YU'],
        ['name' => 'Zacatecas', 'abbreviation' => 'ZA'],
    ];

    foreach ($states as $state) {
        \DB::table('states')->updateOrInsert(['abbreviation' => $state['abbreviation']], $state);
    }
    }
}
