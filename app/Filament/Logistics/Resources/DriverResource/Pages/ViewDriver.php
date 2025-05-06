<?php

namespace App\Filament\Logistics\Resources\DriverResource\Pages;

use App\Filament\Logistics\Resources\DriverResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDriver extends ViewRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
