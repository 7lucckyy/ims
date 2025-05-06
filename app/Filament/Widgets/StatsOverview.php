<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\Indicator;
use App\Models\Project;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class StatsOverview extends BaseWidget
{
    public ?Project $project = null;

    #[On('updateProject')]
    public function updateProject(int $project): void
    {
        $this->project = Project::find($project);
        $this->getStats();
    }

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Indicators', fn () => Indicator::query()
                ->when($this->project, function ($query) {
                    $query->where('project_id', '=', $this->project->id);
                })
                ->count()
            )
                ->icon('heroicon-o-circle-stack'),
            Stat::make('Activities', fn () => Activity::query()
                ->when($this->project, function ($query) {
                    $query->where('project_id', '=', $this->project->id);
                })
                ->count()
            )
                ->icon('heroicon-o-square-3-stack-3d'),
            Stat::make('Tasks', fn () => Task::query()
                ->when($this->project, function ($query) {
                    $query->where('project_id', '=', $this->project->id);
                })
                ->count()
            )
                ->icon('heroicon-o-briefcase'),
        ];
    }
}
