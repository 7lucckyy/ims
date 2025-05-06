<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = User::factory(19)
            ->create()
            ->each(fn (User $user) => $user->assignRole(Role::STAFF));

        $staff->random()->assignRole(Role::HR);
        $staff->random()->assignRole(Role::LOGISTICS);
        $staff->random()->assignRole(Role::FINANCE);
    }
}
