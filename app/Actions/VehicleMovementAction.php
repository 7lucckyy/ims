<?php

namespace App\Actions;

use App\Enums\Enums\VehicleStatus;
use App\Models\VehicleMovement;
use Filament\Actions\Action;

class VehicleMovementAction
{
    public static function headerActions(string|int $id): array
    {

        if (! VehicleMovementAction::isPending($id)) {
            return [
                Action::make('Status')
                    ->label(VehicleMovementAction::status($id))
                    ->badge(),
            ];
        }

        return [
            Action::make('Approve')
                ->label('Approve')
                ->requiresConfirmation()
                ->action(fn () => VehicleMovementAction::approve($id)),

            Action::make('Decline')
                ->label('Decline')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn () => VehicleMovementAction::decline($id)),
        ];
    }

    public static function approve(string $currentId)
    {
        VehicleMovement::find($currentId)->update([
            'approval' => VehicleStatus::Accept->value,
        ]);
    }

    public static function decline(string $currentId)
    {
        VehicleMovement::find($currentId)->update([
            'approval' => VehicleStatus::Decline->value,
        ]);
    }

    public static function isPending(string $currentId): bool
    {
        return VehicleMovement::find($currentId)->approval === 'pending';
    }

    public static function status(string $currentId): string
    {
        return VehicleMovement::find($currentId)->approval;
    }
}
