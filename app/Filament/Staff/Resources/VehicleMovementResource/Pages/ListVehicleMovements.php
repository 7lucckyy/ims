<?php

namespace App\Filament\Staff\Resources\VehicleMovementResource\Pages;

use App\Filament\Staff\Resources\VehicleMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleMovements extends ListRecords
{
    protected static string $resource = VehicleMovementResource::class;

    protected static ?string $title = 'Request for Vehicle';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Request for Vehicle'),
        ];
    }
}
