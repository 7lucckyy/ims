<?php

namespace App\Filament\Resources\BudgetTrenchesResource\Pages;

use App\Filament\Resources\BudgetTrenchesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgetTrenches extends ListRecords
{
    protected static string $resource = BudgetTrenchesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
