<?php

namespace App\Filament\Resources\VehicleMovementResource\Pages;

use App\Actions\VehicleMovementAction;
use App\Filament\Resources\VehicleMovementResource;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleMovement extends ViewRecord
{
    protected static string $resource = VehicleMovementResource::class;

    protected function getHeaderActions(): array
    {
        return VehicleMovementAction::headerActions($this->data['id']);
    }
}
