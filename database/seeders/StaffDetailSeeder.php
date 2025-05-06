<?php

namespace Database\Seeders;

use App\Enums\BloodGroup;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class StaffDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all()->pluck('id');

        foreach (app('staff') as $staff) {
            $staff->staffDetail()->create([
                'department_id' => $department = $departments->random(),
                'position_id' => Position::where('department_id', $department)->get()->pluck('id')->random(),
                'dob' => now()->subYears(fake()->numberBetween(18, 35)),
                'address' => fake()->address(),
                'phone_number' => fake()->e164PhoneNumber(),
                'emergency_contact_number' => fake()->e164PhoneNumber(),
                'blood_group' => fake()->randomElement(BloodGroup::values()),
                'date_of_employment' => now(),
            ]);
        }
    }
}
