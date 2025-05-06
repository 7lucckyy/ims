<?php

namespace App\Filament\Widgets;

use App\Enums\BidStatus;
use App\Models\CompetitiveBid;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class BidAnalysisStats extends BaseWidget
{
    protected static ?int $sort = 3;

    public ?Project $project = null;

    #[On('updateProject')]
    public function updateProject(int $project): void
    {
        $this->project = Project::find($project);
        $this->getStats();
    }

    protected function getStats(): array
    {
        $totalBids = CompetitiveBid::query()
            ->when($this->project, function ($query) {
                $query->where('project_id', '=', $this->project->id);
            })
            ->count();

        $wonBids = CompetitiveBid::query()
            ->where('status', BidStatus::Won->value)
            ->when($this->project, function ($query) {
                $query->where('project_id', '=', $this->project->id);
            })
            ->count();

        $winRate = $totalBids > 0 ? ($wonBids / $totalBids) * 100 : 0;

        $averageVariance = CompetitiveBid::avg('variance_percentage');

        return [
            Stat::make('Total Bids', $totalBids),
            Stat::make('Win Rate', number_format($winRate, 1).'%')
                ->color('success'),
            Stat::make('Average Variance', number_format($averageVariance, 2).'%')
                ->color($averageVariance > 0 ? 'danger' : 'success'),
        ];
    }
}
