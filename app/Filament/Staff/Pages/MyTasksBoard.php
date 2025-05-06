<?php

namespace App\Filament\Staff\Pages;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class MyTasksBoard extends KanbanBoard
{
    protected static string $model = Task::class;

    protected static string $statusEnum = TaskStatus::class;

    public bool $disableEditModal = true;

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

    public function records(): Collection
    {
        return Task::query()
            ->where('user_id', Auth::id())
            ->get();
    }
}
