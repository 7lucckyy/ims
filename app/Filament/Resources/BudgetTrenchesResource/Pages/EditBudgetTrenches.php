<?php

namespace App\Filament\Resources\BudgetTrenchesResource\Pages;

use App\Filament\Resources\BudgetTrenchesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudgetTrenches extends EditRecord
{
    protected static string $resource = BudgetTrenchesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
