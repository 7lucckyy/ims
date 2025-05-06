<?php

namespace App\Filament\Staff\Resources\TaskResource\Pages;

use App\Filament\Staff\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('task')
                ->label('Add Task')
                ->icon('heroicon-o-squares-plus')
                ->form(Task::getForm())
                ->action(function(array $data) {

                    $data['user_id'] = Auth::id();
                    $data['department_id'] = User::find($data['user_id'])->staffDetail->department->id;

                    Task::create($data);
                })
        ];
    }
}
