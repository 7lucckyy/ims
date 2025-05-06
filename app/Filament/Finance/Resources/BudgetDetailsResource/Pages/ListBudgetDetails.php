<?php

namespace App\Filament\Finance\Resources\BudgetDetailsResource\Pages;

use App\Enums\BudgetTrenchStatus;
use App\Filament\Finance\Resources\BudgetDetailsResource;
use App\Models\BudgetTrench;
use App\Models\Project;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Pages\ListRecords;

class ListBudgetDetails extends ListRecords
{
    protected static string $resource = BudgetDetailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->color('primary')
                ->beforeUploadField([
                    Select::make('project_id')
                        ->options(Project::all()->pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->preload()
                        ->required()
                        ->label('Project'),
                    Select::make('budget_trench_id')
                        ->options(fn(Get $get) => BudgetTrench::where('project_id', $get('project_id'))->pluck('code', 'id'))
                        ->visible(fn(Get $get) => $get('project_id'))
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('code')
                                        ->required(),
                                    TextInput::make('amount')
                                        ->required()
                                ]),
                            Grid::make(2)
                                ->schema([
                                    Select::make(name: 'status')
                                        ->default(BudgetTrenchStatus::DEFAULT )
                                        ->enum(BudgetTrenchStatus::class)
                                        ->options(BudgetTrenchStatus::class)
                                        ->searchable()
                                        ->required(),
                                    DatePicker::make('transaction_date')
                                        ->required()
                                ]),
                            Grid::make(2)
                                ->schema([
                                    DatePicker::make('start_date')
                                        ->required(),
                                    DatePicker::make('end_date')
                                        ->required()
                                ]),
                            RichEditor::make('description')
                        ])
                        ->label('Budget Trench')
                        ->createOptionModalHeading('Create Budget Trench')
                        ->createOptionUsing(function (array $data, Get $get): int {

                            $budgetTrench = BudgetTrench::create([
                                'project_id' => $get('project_id'),
                                'code' => $data['code'],
                                'amount' => $data['amount'],
                                'transaction_date' => $data['transaction_date'],
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                                'status' => $data['status'],
                                'description' => $data['description'],
                            ]);

                            return $budgetTrench->getKey();
                        })
                ])
                ->beforeImport(function (array $data, $livewire, $excelImportAction) {

                    $excelImportAction->additionalData([
                        'project_id' => $data['project_id'],
                        'budget_trench_id' => $data['budget_trench_id'],
                    ]);

                }),
        ];
    }
}
