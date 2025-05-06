<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projects = Project::all()->pluck('id');

        return [
            'project_id' => $project = $projects->random(),
            'user_id' => $staff_id = Project::find($project)->users()->get()->pluck('id')->random(),
            'activity_id' => Activity::where('project_id', $project)->get()->pluck('id')->random(),
            'department_id' => User::find($staff_id)->staffDetail->department->id,
            'title' => fake()->sentence(),
            'deadline' => now()->addDays(fake()->numberBetween(1, 10)),
            'description' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(TaskStatus::values()),
        ];
    }
}
