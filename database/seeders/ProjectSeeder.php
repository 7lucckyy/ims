<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Indicator;
use App\Models\Location;
use App\Models\Project;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = app('staff');
        $sectors = Sector::all()->pluck('id');

        Project::factory(10)
            ->create()
            ->each(function (Project $project) use ($staff, $sectors) {

                $project->sectors()->attach($sectors->random(2));

                // Project locations
                Location::factory(4, ['project_id' => $project->id])->create();

                // Project staff
                $project->users()->attach($staff->random(3));

                // Project indicators
                $indicators = Indicator::factory(5, ['project_id' => $project->id])
                    ->create()
                    ->each(function (Indicator $indicator) {

                        $target = 0;

                        for ($i = 0; $i <= fake()->numberBetween(5, 10); $i++) {

                            $indicator->meansOfMeasure()->create([
                                'name' => fake()->company(),
                                'value' => $value = fake()->numberBetween(60, 100),
                            ]);

                            $target += $value;

                        }

                        $indicator->update(['target' => $target]);

                    });

                // Project activities
                Activity::factory(20, ['project_id' => $project->id])
                    ->recycle($indicators)
                    ->create();

            });
    }
}
