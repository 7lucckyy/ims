<?php

namespace App\Filament\Logistics\Resources\StockOutResource\Pages;

use App\Actions\StockAction;
use App\Filament\Logistics\Resources\StockOutResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockOut extends CreateRecord
{
    protected static string $resource = StockOutResource::class;

    public function afterCreate(): void
    {
        StockAction::updateRecordAction($this->data);
    }
}
