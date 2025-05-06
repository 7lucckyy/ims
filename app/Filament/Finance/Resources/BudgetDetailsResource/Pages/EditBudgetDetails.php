<?php

namespace App\Filament\Finance\Resources\BudgetDetailsResource\Pages;

use App\Filament\Finance\Resources\BudgetDetailsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudgetDetails extends EditRecord
{
    protected static string $resource = BudgetDetailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
