<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\AttendanceFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CurrencySeeder::class,
            SectorSeeder::class,
        //     // StaffSeeder::class,
            DepartmentSeeder::class,
        //     // PositionSeeder::class,
        //     // ProjectSeeder::class,
            CategorySeeder::class,
        //     // StaffDetailSeeder::class,
        //     // TaskSeeder::class,
        ]);


        AttendanceFactory::times(1)->create();
    }
}
