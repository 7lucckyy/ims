<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'Abia', 'iso_code_2' => 'AB'],
            ['name' => 'Adamawa', 'iso_code_2' => 'AD'],
            ['name' => 'Akwa Ibom', 'iso_code_2' => 'AK'],
            ['name' => 'Anambra', 'iso_code_2' => 'AN'],
            ['name' => 'Bauchi', 'iso_code_2' => 'BA'],
            ['name' => 'Bayelsa', 'iso_code_2' => 'BY'],
            ['name' => 'Benue', 'iso_code_2' => 'BE'],
            ['name' => 'Borno', 'iso_code_2' => 'BO'],
            ['name' => 'Cross River', 'iso_code_2' => 'CR'],
            ['name' => 'Delta', 'iso_code_2' => 'DE'],
            ['name' => 'Ebonyi', 'iso_code_2' => 'EB'],
            ['name' => 'Edo', 'iso_code_2' => 'ED'],
            ['name' => 'Ekiti', 'iso_code_2' => 'EK'],
            ['name' => 'Enugu', 'iso_code_2' => 'EN'],
            ['name' => 'Federal Capital Territory', 'iso_code_2' => 'FC'],
            ['name' => 'Gombe', 'iso_code_2' => 'GO'],
            ['name' => 'Imo', 'iso_code_2' => 'IM'],
            ['name' => 'Jigawa', 'iso_code_2' => 'JI'],
            ['name' => 'Kaduna', 'iso_code_2' => 'KD'],
            ['name' => 'Kano', 'iso_code_2' => 'KN'],
            ['name' => 'Katsina', 'iso_code_2' => 'KT'],
            ['name' => 'Kebbi', 'iso_code_2' => 'KE'],
            ['name' => 'Kogi', 'iso_code_2' => 'KO'],
            ['name' => 'Kwara', 'iso_code_2' => 'KW'],
            ['name' => 'Lagos', 'iso_code_2' => 'LA'],
            ['name' => 'Nasarawa', 'iso_code_2' => 'NA'],
            ['name' => 'Niger', 'iso_code_2' => 'NI'],
            ['name' => 'Ogun', 'iso_code_2' => 'OG'],
            ['name' => 'Ondo', 'iso_code_2' => 'ON'],
            ['name' => 'Osun', 'iso_code_2' => 'OS'],
            ['name' => 'Oyo', 'iso_code_2' => 'OY'],
            ['name' => 'Plateau', 'iso_code_2' => 'PL'],
            ['name' => 'Rivers', 'iso_code_2' => 'RI'],
            ['name' => 'Sokoto', 'iso_code_2' => 'SO'],
            ['name' => 'Taraba', 'iso_code_2' => 'TA'],
            ['name' => 'Yobe', 'iso_code_2' => 'YO'],
            ['name' => 'Zamfara', 'iso_code_2' => 'ZA'],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
