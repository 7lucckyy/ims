<?php

namespace App\Filament\Logistics\Resources\VehicleMovementResource\Pages;

use App\Filament\Logistics\Resources\VehicleMovementResource;
use App\Actions\VehicleMovementAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleMovement extends ViewRecord
{
    protected static string $resource = VehicleMovementResource::class;

    protected function getHeaderActions(): array
    {
        return VehicleMovementAction::headerActions($this->data['id']);
    }
}
