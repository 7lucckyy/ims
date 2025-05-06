<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['MEAL', 'Procurement', 'Logistics', 'HR', 'Program', 'Finance', 'Tech4D'];

        $staff = User::role(Role::STAFF)->get()->pluck('id');

        foreach ($departments as $department) {
            Department::create([
                'name' => $department,
                'hod_id' => $staff->random(),
            ]);
        }

    }
}
