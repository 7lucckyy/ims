<?php

namespace App\Filament\Widgets;

use App\Models\Indicator;
use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Livewire\Attributes\On;

class IndicatorChart extends ChartWidget
{
    protected static ?string $heading = 'Indicators';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '300px';

    protected static ?string $minHeight = '300px';

    public ?Project $project = null;

    public ?string $filter = 'quarterly';

    protected function getFilters(): array
    {
        return [
            'week' => 'This Week',
            'month' => 'This Month',
            'quarterly' => 'Last 3 Months',
            'year' => 'This Year',
        ];
    }

    #[On('updateProject')]
    public function updateProject(int $project): void
    {
        $this->project = Project::find($project);
        $this->getData();
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        $indicators = Indicator::query()
            ->when($this->project, function ($query) {
                $query->where('project_id', $this->project->id);
            });

        match ($filter) {
            'week' => $data = Trend::query($indicators)
                ->between(
                    start: now()->startOfWeek(),
                    end: now()->endOfWeek()
                )
                ->perDay()
                ->count(),
            'month' => $data = Trend::query($indicators)
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth()
                )
                ->perWeek()
                ->count(),
            'quarterly' => $data = Trend::query($indicators)
                ->between(
                    start: now()->subMonths(3),
                    end: now()
                )
                ->perWeek()
                ->count(),
            'year' => $data = Trend::query($indicators)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear()
                )
                ->perMonth()
                ->count(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Indicators',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
