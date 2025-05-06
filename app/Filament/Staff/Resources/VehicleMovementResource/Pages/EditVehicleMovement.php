<?php

namespace App\Filament\Staff\Resources\VehicleMovementResource\Pages;

use App\Filament\Staff\Resources\VehicleMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleMovement extends EditRecord
{
    protected static string $resource = VehicleMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
