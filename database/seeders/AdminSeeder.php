<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory([
            'name' => 'Admin User',
            'email' => 'admin@goalprime.org',
        ])
            ->create();

        $admin->assignRole([Role::ADMIN, Role::STAFF]);
    }
}
