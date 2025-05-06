<?php

namespace App\Filament\Logistics\Resources\VehicleMovementResource\Pages;

use App\Filament\Logistics\Resources\VehicleMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleMovements extends ListRecords
{
    protected static string $resource = VehicleMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
