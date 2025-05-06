<?php

namespace App\Filament\Logistics\Resources\StockOutResource\Pages;

use App\Filament\Logistics\Resources\StockOutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockOuts extends ListRecords
{
    protected static string $resource = StockOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
