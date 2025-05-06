<?php

namespace App\Filament\Widgets;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class TasksChart extends ChartWidget
{
    protected static ?string $heading = 'Tasks';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '300px';

    public ?Project $project = null;

    #[On('updateProject')]
    public function updateProject(int $project): void
    {
        $this->project = Project::find($project);
        $this->getData();
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Tasks',
                    'data' => [
                        Task::query()
                            ->when($this->project, function ($query) {
                                $query->where('project_id', $this->project->id);
                            })
                            ->where('status', TaskStatus::Todo)
                            ->count(),
                        Task::query()
                            ->when($this->project, function ($query) {
                                $query->where('project_id', $this->project->id);
                            })
                            ->where('status', TaskStatus::InProgress)
                            ->count(),
                        Task::query()
                            ->when($this->project, function ($query) {
                                $query->where('project_id', $this->project->id);
                            })
                            ->where('status', TaskStatus::Reviewing)
                            ->count(),
                        Task::query()
                            ->when($this->project, function ($query) {
                                $query->where('project_id', $this->project->id);
                            })
                            ->where('status', TaskStatus::Done)
                            ->count(),
                    ],
                    'backgroundColor' => [
                        '#71717a',
                        '#f97316',
                        '#3b82f6',
                        '#22c55e',
                    ],
                    'borderColor' => [
                        '#71717a',
                        '#f97316',
                        '#3b82f6',
                        '#22c55e',
                    ],
                ],
            ],
            'labels' => [
                TaskStatus::Todo,
                TaskStatus::InProgress,
                TaskStatus::Reviewing,
                TaskStatus::Done,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
