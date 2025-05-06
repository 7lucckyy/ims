<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Filament\Staff\Resources\TaskResource as ResourcesTaskResource;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['department_id'] = User::find($data['user_id'])->staffDetail->department->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        $recipient = User::find($record->user->id);

        $recipient->notify(
            Notification::make()
                ->title('New Task')
                ->body('Task for project '.$record->code)
                ->icon('heroicon-o-briefcase')
                ->iconColor('info')
                ->actions([
                    Action::make('view')
                        ->markAsRead()
                        ->color('info')
                        ->icon('heroicon-o-eye')
                        ->url(ResourcesTaskResource::getUrl('view', ['record' => $record->id], panel: 'staff')),
                ])
                ->toDatabase()

        );
    }
}
