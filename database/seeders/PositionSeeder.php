<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Department::all() as $department) {
            $positions = ['Manager', 'Operations', 'Project Manager', 'Product Manager'];

            foreach ($positions as $position) {
                $department->positions()->create([
                    'name' => $position,
                ]);
            }
        }
    }
}
