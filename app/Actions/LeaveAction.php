<?php

namespace App\Actions;

use App\Enums\LeaveStatus;
use App\Models\Leave;
use Filament\Actions\Action;

class LeaveAction
{
    public static function headerActions(string|int $id): array
    {
        $leave = Leave::find($id);
        if ($leave->approval === LeaveStatus::Pending->value) {
            return [
                Action::make('Accept')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn () => static::approve($id)),
                Action::make('Decline')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn () => static::reject($id)),
            ];
        }

        return [
            Action::make('Approval')
                ->label($leave->approval)
                ->badge(),
        ];
    }

    public static function approve(string|int $id): void
    {
        Leave::find($id)->update(['approval' => LeaveStatus::Approved]);
    }

    public static function reject(string|int $id)
    {
        Leave::find($id)->update(['approval' => LeaveStatus::Rejected]);
    }
}
