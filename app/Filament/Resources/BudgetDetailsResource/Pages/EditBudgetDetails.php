<?php

namespace App\Filament\Resources\BudgetDetailsResource\Pages;

use App\Filament\Resources\BudgetDetailsResource;
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
