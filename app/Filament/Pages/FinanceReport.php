<?php

namespace App\Filament\Resources\Pages;

use App\Models\Project;
use App\Models\BudgetDetail;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class FinanceReport extends Page
{
    use InteractsWithInfolists;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Financial Reports';
    protected static ?int $navigationSort = 0;
    protected static string $view = 'filament.pages.finance-report';

    public ?int $project_id = null;
    public Collection $budgetDetails;

    public function mount()
    {
        $this->budgetDetails = collect();
    }

    public function updatedProjectId()
    {
        $this->budgetDetails = BudgetDetail::where('project_id', $this->project_id)->get();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Select::make('project_id')
                        ->label('Select Project')
                        ->options(Project::pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(fn() => $this->updatedProjectId()),
                ]),
        ];
    }

    public function getInfolist(string $name): ?Infolist
    {
        if ($name !== 'default') {
            return null;
        }

        return Infolist::make()
            ->state($this->budgetDetails->toArray())
            ->schema([
                TextEntry::make('line')->label('Budget Line'),
                TextEntry::make('amount')->label('Total Budget')->money(),
                TextEntry::make('remaining_amount')->label('Remaining Balance')->money(),
                TextEntry::make('spent_amount')
                    ->label('Spent Amount')
                    ->formatStateUsing(fn($state, $record) => $record['amount'] - $record['remaining_amount'])
                    ->money(),
                TextEntry::make('percentage_spent')
                    ->label('Spent (%)')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record['amount'] > 0
                        ? round((($record['amount'] - $record['remaining_amount']) / $record['amount']) * 100, 2) . '%'
                        : '0%'
                    )
                    ->color('danger'),
                TextEntry::make('percentage_remaining')
                    ->label('Remaining (%)')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record['amount'] > 0
                        ? round(($record['remaining_amount'] / $record['amount']) * 100, 2) . '%'
                        : '0%'
                    )
                    ->color('success'),
            ]);
    }
}
