<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sector::create(['name' => 'WASH']);
        Sector::create(['name' => 'EDUCATION']);
        Sector::create(['name' => 'CHILD PROTECTION']);
    }
}
