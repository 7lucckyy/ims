<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Staff']);
        Role::create(['name' => 'Logistics']);
        Role::create(['name' => 'Finance']);
        Role::create(['name' => 'Hr']);
        Role::create(['name' => 'Procurement']);
        Role::create(['name' => 'Meal']);
        Role::create(['name' => 'Driver']);
        Role::create(['name' => 'Donor']);
    }
}
