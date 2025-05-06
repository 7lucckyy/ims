<?php

namespace App\Filament\Hr\Resources\LeaveResource\Pages;

use App\Enums\LeaveStatus;
use App\Filament\Resources\LeaveResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewLeave extends ViewRecord
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('approve')
                    ->icon(LeaveStatus::Approved->getIcon())
                    ->color(LeaveStatus::Approved->getColor())
                    ->hidden(fn ($record) => $record->approval->value === LeaveStatus::Approved->value)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->approve()),
                Action::make('reject')
                    ->icon(LeaveStatus::Rejected->getIcon())
                    ->color(LeaveStatus::Rejected->getColor())
                    ->hidden(fn ($record) => $record->approval->value === LeaveStatus::Rejected->value)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->reject()),
            ]),
        ];
    }
}
