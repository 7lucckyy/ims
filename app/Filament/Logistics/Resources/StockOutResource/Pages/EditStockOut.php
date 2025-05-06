<?php

namespace App\Filament\Logistics\Resources\StockOutResource\Pages;

use App\Filament\Logistics\Resources\StockOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockOut extends EditRecord
{
    protected static string $resource = StockOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
