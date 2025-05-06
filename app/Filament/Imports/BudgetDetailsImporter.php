<?php

namespace App\Filament\Imports;

use App\Models\BudgetDetail;
use App\Models\Project;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;

class BudgetDetailsImporter extends Importer
{
    protected static ?string $model = BudgetDetail::class;

    protected ?int $projectId = null;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('line')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('amount')
                ->requiredMapping()
                ->rules(['required', 'min:0', 'numeric']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('project_id')
                ->options(Project::all()->pluck('name', 'id'))
                ->required(),
        ];
    }

    public static function getFormSchema(): array
    {
        return [
            Select::make('project_id')
                ->label('Select Project')
                ->options(Project::all()->pluck('name', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(fn($state, $set) => $set('projectId', $state)),
        ];
    }

    public function beforeImport(array $data): array
    {
        if (!$this->projectId) {
            throw new \Exception('You must select a project before importing data.');
        }

        foreach ($data as &$row) {
            $row['project_id'] = $this->projectId;
        }

        return $data;
    }

    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your budget details import has completed with ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
